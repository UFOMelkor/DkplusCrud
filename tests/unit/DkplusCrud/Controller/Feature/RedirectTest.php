<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.2.0
 */
class RedirectTest extends TestCase
{
    /** @var \Zend\Mvc\Controller\Plugin\Redirect|\PHPUnit_Framework_MockObject_MockObject */
    protected $redirector;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var Redirect */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();

        $this->redirector = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect');

        $controller = $this->getMock('DkplusCrud\Controller\Controller', array('redirect'));
        $controller->expects($this->any())->method('redirect')->will($this->returnValue($this->redirector));

        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($controller));
    }

    /** @test */
    public function isAFeature()
    {
        $feature = new Redirect('my-route');
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $feature);
    }

    /** @test */
    public function attachesItselfToThePostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())->method('attach')->with('postDelete');

        $feature = new Redirect('my-redirect');
        $feature->attachTo('delete', $events);
    }

    /** @test */
    public function redirectsToARoute()
    {
        $response = $this->getMock('Zend\Http\Response');
        $this->redirector->expects($this->once())->method('toRoute')->with('bar')->will($this->returnValue($response));

        $feature = new Redirect('bar');
        $feature->execute($this->event);
    }

    /** @test */
    public function mayUseRouteParamsToRedirect()
    {
        $routeParams = array('action' => 'my-action');
        $this->redirector->expects($this->once())
                         ->method('toRoute')
                         ->with($this->anything(), $routeParams)
                         ->will($this->returnValue($this->getMock('Zend\Http\Response')));

        $feature = new Redirect('my-route', $routeParams);
        $feature->execute($this->event);
    }

    /** @test */
    public function putsTheRedirectIntoTheEvent()
    {
        $response = $this->getMock('Zend\Http\Response');
        $this->redirector->expects($this->any())->method('toRoute')->will($this->returnValue($response));

        $this->event->expects($this->once())->method('setResponse')->with($response);

        $feature = new Redirect('any-route');
        $feature->execute($this->event);
    }

    /** @test */
    public function canUseTheCurrentEntityToComputateRedirectParameters()
    {
        $entity      = $this->getMock('stdClass');
        $routeParams = array('foo' => 'bar');

        $callbackHandler = $this->getMock('stdClass', array('computateRouteParams'));
        $callbackHandler->expects($this->any())
                        ->method('computateRouteParams')
                        ->with($entity)
                        ->will($this->returnValue($routeParams));

        $this->event->expects($this->any())
                    ->method('hasEntity')
                    ->will($this->returnValue(true));
        $this->event->expects($this->any())
                    ->method('getEntity')
                    ->will($this->returnValue($entity));

        $response = $this->getMock('Zend\Http\Response');
        $this->redirector->expects($this->any())->method('toRoute')->will($this->returnValue($response));

        $feature = new Redirect('a-route', array($callbackHandler, 'computateRouteParams'));
        $feature->execute($this->event);
    }

    /** @test */
    public function mustNotHaveAnEntityToComputateTheRedirectParameters()
    {
        $routeParams = array('foo' => 'bar');

        $callbackHandler = $this->getMock('stdClass', array('computateRouteParams'));
        $callbackHandler->expects($this->any())
                        ->method('computateRouteParams')
                        ->will($this->returnValue($routeParams));

        $this->event->expects($this->any())
                    ->method('hasEntity')
                    ->will($this->returnValue(false));
        $this->event->expects($this->never())
                    ->method('getEntity');

        $response = $this->getMock('Zend\Http\Response');
        $this->redirector->expects($this->any())->method('toRoute')->will($this->returnValue($response));

        $feature = new Redirect('a-route', array($callbackHandler, 'computateRouteParams'));
        $feature->execute($this->event);
    }

    /** @test */
    public function canDoNothingIfNoEntityExists()
    {
        $this->event->expects($this->any())->method('hasEntity')->will($this->returnValue(false));

        $this->redirector->expects($this->never())->method('toRoute');

        $feature = new Redirect('never used route', array(), false);
        $feature->execute($this->event);
    }

    /** @test */
    public function ifItShouldDoNothingOnNotExistingEntitiesItDoesOnExistingEntities()
    {
        $this->event->expects($this->any())->method('hasEntity')->will($this->returnValue(true));

        $response = $this->getMock('Zend\Http\Response');
        $this->redirector->expects($this->once())->method('toRoute')->will($this->returnValue($response));

        $feature = new Redirect('used route', array(), false);
        $feature->execute($this->event);
    }
}

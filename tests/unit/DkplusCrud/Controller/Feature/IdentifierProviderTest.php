<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class IdentifierProviderTest extends TestCase
{
    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Zend\Http\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $routeMatch;

    /** @var DkplusCrud\Controller\Controller */
    protected $controller;

    protected function setUp()
    {
        $this->event      = $this->getMockBuilder('DkplusCrud\Controller\Event')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        $this->routeMatch = $this->getMockBuilder('Zend\Mvc\Router\RouteMatch')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $mvcEvent = $this->getMock('Zend\Mvc\MvcEvent');
        $mvcEvent->expects($this->any())->method('getRouteMatch')->will($this->returnValue($this->routeMatch));

        $this->controller = $this->getMock('DkplusCrud\Controller\Controller');
        $this->controller->expects($this->any())->method('getEvent')->will($this->returnValue($mvcEvent));
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($this->controller));
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            new IdentifierProvider()
        );
    }

    /** @test */
    public function attachesItselfToThePreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())->method('attach')->with('preDelete');

        $feature = new IdentifierProvider();
        $feature->attachTo('delete', $events);
    }

    /** @test */
    public function attachesItselfWithAPriorityOfTwo()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())->method('attach')->with($this->isType('string'), $this->isType('array'), 2);

        $feature = new IdentifierProvider();
        $feature->attachTo('delete', $events);
    }

    /** @test */
    public function putsTheIdentifierFromTheRouteMatchIntoTheEvent()
    {
        $this->event->expects($this->once())->method('setIdentifier')->with(5);

        $this->routeMatch->expects($this->any())->method('getParam')->will($this->returnValue(5));

        $feature = new IdentifierProvider();
        $feature->execute($this->event);
    }

    /** @test */
    public function hasIdAsDefaultRouteMatchParam()
    {
        $this->routeMatch->expects($this->once())->method('getParam')->with('id');

        $feature = new IdentifierProvider();
        $feature->execute($this->event);
    }

    /** @test */
    public function mightHaveAnotherRouteMatchParamForGettingTheIdentifier()
    {
        $this->routeMatch->expects($this->once())->method('getParam')->with('my-identifier');

        $feature = new IdentifierProvider('my-identifier');
        $feature->execute($this->event);
    }
}

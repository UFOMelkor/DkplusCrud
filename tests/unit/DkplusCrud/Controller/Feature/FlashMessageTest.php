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
class FlashMessageTest extends TestCase
{
    /** @var \Zend\Mvc\Controller\Plugin\FlashMessenger|\PHPUnit_Framework_MockObject_MockObject */
    protected $flashMessenger;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var FlashMessage */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();

        $this->flashMessenger = $this->getMock('Zend\Mvc\Controller\Plugin\FlashMessenger');

        $controller = $this->getMock('DkplusCrud\Controller\Controller', array('flashMessenger'));
        $controller->expects($this->any())->method('flashMessenger')->will($this->returnValue($this->flashMessenger));

        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($controller));
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', new FlashMessage('my-message'));
    }

    /** @test */
    public function attachesItselfToThePostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postDelete');

        $feature = new FlashMessage('my-message');
        $feature->attachTo('delete', $events);
    }

    /** @test */
    public function addsAFlashMessage()
    {
        $this->flashMessenger->expects($this->once())->method('addMessage')->with('bar');

        $feature = new FlashMessage('bar');
        $feature->execute($this->event);
    }

    /** @test */
    public function setsNoNamespaceIfNoNamespaceHasBeenSet()
    {
        $this->flashMessenger->expects($this->never())->method('setNamespace');

        $feature = new FlashMessage('my-message');
        $feature->execute($this->event);
    }

    /** @test */
    public function maySetANamespaceIfItHasBeenSet()
    {
        $this->flashMessenger->expects($this->once())->method('setNamespace')->with('foo');

        $feature = new FlashMessage('my-message', 'foo');
        $feature->execute($this->event);
    }


    /** @test */
    public function canUseTheCurrentEntityToComputateTheMessage()
    {
        $entity  = $this->getMock('stdClass');
        $message = 'my-message';

        $callbackHandler = $this->getMock('stdClass', array('computateMessage'));
        $callbackHandler->expects($this->any())
                        ->method('computateMessage')
                        ->with($entity)
                        ->will($this->returnValue($message));

        $this->event->expects($this->any())
                    ->method('hasEntity')
                    ->will($this->returnValue(true));
        $this->event->expects($this->any())
                    ->method('getEntity')
                    ->will($this->returnValue($entity));

        $this->flashMessenger->expects($this->once())->method('addMessage')->with($message);

        $feature = new FlashMessage(array($callbackHandler, 'computateMessage'));
        $feature->execute($this->event);
    }

    /** @test */
    public function mustNotHaveAnEntityToComputateTheMessage()
    {
        $message = 'my-message';

        $callbackHandler = $this->getMock('stdClass', array('computateMessage'));
        $callbackHandler->expects($this->any())
                        ->method('computateMessage')
                        ->will($this->returnValue($message));

        $this->event->expects($this->any())
                    ->method('hasEntity')
                    ->will($this->returnValue(false));
        $this->event->expects($this->never())
                    ->method('getEntity');

        $this->flashMessenger->expects($this->once())->method('addMessage')->with($message);

        $feature = new FlashMessage(array($callbackHandler, 'computateMessage'));
        $feature->execute($this->event);
    }

    /** @test */
    public function canDoNothingIfNoEntityExists()
    {
        $this->event->expects($this->any())->method('hasEntity')->will($this->returnValue(false));

        $this->flashMessenger->expects($this->never())->method('addMessage');

        $feature = new FlashMessage('never used message', null, false);
        $feature->execute($this->event);
    }

    /** @test */
    public function ifItShouldDoNothingOnNotExistingEntitiesItDoesOnExistingEntities()
    {
        $this->event->expects($this->any())->method('hasEntity')->will($this->returnValue(true));

        $this->flashMessenger->expects($this->once())->method('addMessage');

        $feature = new FlashMessage('used message', null, false);
        $feature->execute($this->event);
    }
}

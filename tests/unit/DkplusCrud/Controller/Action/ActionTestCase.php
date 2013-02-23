<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

use Zend\EventManager\ResponseCollection;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class ActionTestCase extends TestCase
{
    /** @var string */
    protected $actionName;

    /** @var ActionInterface */
    protected $action;

    /** @var \Zend\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $eventManager;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        parent::setUp();

        $this->eventManager = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $this->action->attachTo($this->eventManager);

        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                            ->disableOriginalConstructor()
                            ->getMock();

        if ($this->action instanceof AbstractAction) {
            $this->action->setEvent($this->event);
        }
    }

    protected function expectPreEventToBeTriggered()
    {
        $this->eventManager->expects($this->at(0))
                           ->method('trigger')
                           ->with('pre' . \ucFirst($this->actionName), $this->event);
    }

    protected function expectMainEventToBeTriggered()
    {
        $this->eventManager->expects($this->at(1))
                           ->method('trigger')
                           ->with($this->actionName, $this->event);
    }

    protected function expectPostEventToBeTriggered()
    {
        $this->eventManager->expects($this->at(2))
                           ->method('trigger')
                           ->with('post' . \ucFirst($this->actionName), $this->event);
    }

    protected function expectNotFoundEventToBeTriggered()
    {
        $this->eventManager->expects($this->at(1))
                           ->method('trigger')
                           ->with('notFound' . \ucFirst($this->actionName), $this->event);
    }

    protected function expectCountOfTriggeredEvents($count)
    {
        $this->eventManager->expects($this->exactly($count))
                           ->method('trigger');
    }
}

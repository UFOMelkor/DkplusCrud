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

    protected function setUp()
    {
        parent::setUp();
        $this->eventManager = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $this->action->attachTo($this->eventManager);
    }

    /**
     * @param mixed $result
     * @return \Zend\EventManager\ResponseCollection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEventResponseCollectionWithAValidResult($result)
    {
        $eventResult = $this->getMockBuilder('Zend\EventManager\ResponseCollection')
                            ->disableOriginalConstructor()
                            ->getMock();
        $eventResult->expects($this->any())
                    ->method('last')
                    ->will($this->returnValue($result));
        $eventResult->expects($this->any())
                    ->method('stopped')
                    ->will($this->returnValue(true));
        $eventResult->expects($this->any())
                    ->method('count')
                    ->will($this->returnValue(1));
        return $eventResult;
    }

    /**
     * @param mixed $result
     * @return \Zend\EventManager\ResponseCollection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEventResponseCollectionWithAnInvalidResult($result = null)
    {
        $eventResult = $this->getMockBuilder('Zend\EventManager\ResponseCollection')
                            ->disableOriginalConstructor()
                            ->getMock();
        $eventResult->expects($this->any())
                    ->method('last')
                    ->will($this->returnValue($result));
        $eventResult->expects($this->any())
                    ->method('stopped')
                    ->will($this->returnValue(false));
        $eventResult->expects($this->any())
                    ->method('count')
                    ->will($this->returnValue(1));
        return $eventResult;
    }

    /** @return \Zend\EventManager\ResponseCollection|\PHPUnit_Framework_MockObject_MockObject */
    protected function getEventResponseCollectionWithoutResults()
    {
        $eventResult = $this->getMockBuilder('Zend\EventManager\ResponseCollection')
                            ->disableOriginalConstructor()
                            ->getMock();
        $eventResult->expects($this->any())
                    ->method('last')
                    ->will($this->returnValue('foo'));
        $eventResult->expects($this->any())
                    ->method('stopped')
                    ->will($this->returnValue(false));
        $eventResult->expects($this->any())
                    ->method('count')
                    ->will($this->returnValue(1));
        return $eventResult;
    }

    /**
     * @param ResponseCollection $result
     * @param callable|null $callback
     */
    protected function preEventReturns(ResponseCollection $result, $callback = null)
    {
        $eventName = 'pre' . ucFirst($this->actionName);

        if ($callback === null) {
            $callback = $this->isType('callable');
        }

        $this->eventManager->expects($this->at(0))
                           ->method('trigger')
                           ->with($eventName, $this->action, array(), $callback)
                           ->will($this->returnValue($result));
    }

    /**
     * @param ResponseCollection $result
     * @param callable|null $callback
     */
    protected function notFoundEventReturns(ResponseCollection $result, $callback = null)
    {
        $eventName = 'notFound' . ucFirst($this->actionName);

        if ($callback === null) {
            $callback = $this->isType('callable');
        }

        $this->eventManager->expects($this->at(1))
                           ->method('trigger')
                           ->with($eventName, $this->action, array(), $callback)
                           ->will($this->returnValue($result));
    }

    /**
     * @param ResponseCollection $result
     * @param array $parameters
     */
    protected function mainEventReturns(ResponseCollection $result, array $parameters = null, $callback = null)
    {
        if ($parameters === null) {
            $parameters = $this->isType('array');
        }
        if ($callback === null) {
            $callback = $this->isType('callable');
        }
        $this->eventManager->expects($this->at(1))
                           ->method('trigger')
                           ->with($this->actionName, $this->action, $parameters, $callback)
                           ->will($this->returnValue($result));
    }

    /**
     * @param ResponseCollection $result
     * @param array $parameters
     */
    protected function postEventIsTriggeredWith(array $parameters)
    {
        $eventName = 'post' . ucFirst($this->actionName);

        $this->eventManager->expects($this->at(2))
                           ->method('trigger')
                           ->with($eventName, $this->action, $parameters);
    }
}

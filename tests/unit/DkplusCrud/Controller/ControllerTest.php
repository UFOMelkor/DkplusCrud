<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller;

use Zend\Mvc\MvcEvent;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 * @covers DkplusCrud\Controller\Controller
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Controller */
    protected $controller;

    /** @var \Zend\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $events;

    protected function setUp()
    {
        $this->controller = new Controller();
        $this->events     = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
    }

    /** @test */
    public function attachesTheOnDispatchMethodFurthermoreToTheDispatchEvent()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with(MvcEvent::EVENT_DISPATCH, array($this->controller, 'onDispatch'));

        $this->controller->setEventManager($this->events);
    }

    /** @test */
    public function attachesTheEventManagerToEachAddedAction()
    {
        $action = $this->getMockForAbstractClass('DkplusCrud\Controller\Action\ActionInterface');
        $action->expects($this->once())
               ->method('attachTo')
               ->with($this->events);

        $this->controller->addAction($action);
        $this->controller->setEventManager($this->events);
    }

    /** @test */
    public function putsItselfIntoEachAddedAction()
    {
        $action = $this->getMockForAbstractClass('DkplusCrud\Controller\Action\ActionInterface');
        $action->expects($this->once())
               ->method('setController')
               ->with($this->controller);

        $this->controller->addAction($action);
    }

    /** @test */
    public function canAddFeaturesToAddedActions()
    {
        $feature = $this->getMockForAbstractClass('DkplusCrud\Controller\Feature\FeatureInterface');

        $action = $this->getNamedAction('paginate');
        $action->expects($this->once())
               ->method('addFeature')
               ->with($feature);

        $this->controller->addAction($action);
        $this->controller->addFeature('paginate', $feature);
    }

    /** @return Action\ActionInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected function getNamedAction($actionName)
    {
        $action = $this->getMockForAbstractClass('DkplusCrud\Controller\Action\ActionInterface');
        $action->expects($this->any())
               ->method('getName')
               ->will($this->returnValue($actionName));
        return $action;
    }

    /**
     * @test
     * @expectedException OutOfBoundsException
     */
    public function throwsAnExceptionIfAFeatureShouldBeAddedToANonExistingAction()
    {
        $feature = $this->getMockForAbstractClass('DkplusCrud\Controller\Feature\FeatureInterface');
        $this->controller->addFeature('paginate', $feature);
    }

    /** @test */
    public function usesParentDispatchingWhenNoActionCouldBeFound()
    {
        $event  = $this->getEventWithRouteMatch('index');
        $result = $this->controller->onDispatch($event);

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals('Placeholder page', $result->getVariable('content'));
    }

    /**
     * @param string $action
     * @return \Zend\Mvc\MvcEvent|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEventWithRouteMatch($action = null)
    {
        $routeMatch = $this->getMockBuilder('Zend\Mvc\Router\RouteMatch')
                           ->disableOriginalConstructor()
                           ->getMock();
        if ($action !== null) {
            $routeMatch->expects($this->any())
                       ->method('getParam')
                       ->with('action')
                       ->will($this->returnValue($action));
        }

        $event = $this->getMock('Zend\Mvc\MvcEvent');
        $event->expects($this->any())
              ->method('getRouteMatch')
              ->will($this->returnValue($routeMatch));
        return $event;
    }

    /** @test */
    public function getsTheResultFromAnActionWhenAnActionHasBeenFound()
    {
        $expectedResult = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $action = $this->getNamedAction('index');
        $action->expects($this->any())
               ->method('execute')
               ->will($this->returnValue($expectedResult));
        $this->controller->addAction($action);

        $event = $this->getEventWithRouteMatch('index');
        $this->assertSame($expectedResult, $this->controller->onDispatch($event));
    }

    /** @test */
    public function putsTheResultAsResultIntoTheEvent()
    {
        $expectedResult = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $action = $this->getNamedAction('index');
        $action->expects($this->any())
               ->method('execute')
               ->will($this->returnValue($expectedResult));
        $this->controller->addAction($action);

        $event = $this->getEventWithRouteMatch('index');
        $event->expects($this->once())
              ->method('setResult')
              ->with($expectedResult);

        $this->controller->onDispatch($event);
    }
}

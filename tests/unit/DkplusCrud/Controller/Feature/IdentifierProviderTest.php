<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class IdentifierProviderTest extends TestCase
{
    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Zend\Http\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $routeMatch;

    /** @var DkplusCrud\Controller\Controller */
    protected $controller;

    protected function setUp()
    {
        $this->event   = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');

        $this->routeMatch = $this->getMockBuilder('Zend\Mvc\Router\RouteMatch')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $mvcEvent = $this->getMock('Zend\Mvc\MvcEvent');
        $mvcEvent->expects($this->any())
                 ->method('getRouteMatch')
                 ->will($this->returnValue($this->routeMatch));

        $this->controller = $this->getMock('DkplusCrud\Controller\Controller');
        $this->controller->expects($this->any())
                         ->method('getEvent')
                         ->will($this->returnValue($mvcEvent));
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isAFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            new IdentifierProvider()
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesItselfAsPreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('preDelete');

        $feature = new IdentifierProvider();
        $feature->attachTo('delete', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesItselfWithAPriorityOfTwo()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with($this->isType('string'), $this->isType('array'), 2);

        $feature = new IdentifierProvider();
        $feature->attachTo('delete', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function putsTheIdentifierFromTheRouteMatchIntoTheEvent()
    {
        $this->event->expects($this->once())
                    ->method('setParam')
                    ->with('identifier', 5);

        $this->routeMatch->expects($this->any())
                         ->method('getParam')
                         ->will($this->returnValue(5));

        $feature = new IdentifierProvider();
        $feature->setController($this->controller);

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function hasIdAsDefaultRouteMatchParam()
    {
        $this->routeMatch->expects($this->once())
                         ->method('getParam')
                         ->with('id');

        $feature = new IdentifierProvider();
        $feature->setController($this->controller);

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function mightHaveAnotherRouteMatchParamForGettingTheIdentifier()
    {
        $this->routeMatch->expects($this->once())
                         ->method('getParam')
                         ->with('my-identifier');

        $feature = new IdentifierProvider('my-identifier');
        $feature->setController($this->controller);

        $feature->execute($this->event);
    }
}

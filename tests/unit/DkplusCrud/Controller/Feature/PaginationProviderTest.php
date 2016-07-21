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
class PaginationProviderTest extends TestCase
{
    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \DkplusCrud\Controller\Event\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Zend\Http\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $routeMatch;

    /** @var DkplusCrud\Controller\Controller */
    protected $controller;

    protected function setUp()
    {
        $this->event   = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');

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
            new PaginationProvider($this->service)
        );
    }

    /** @test */
    public function attachesItselfToThePreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('prePaginate');

        $feature = new PaginationProvider($this->service);
        $feature->attachTo('paginate', $events);
    }

    /** @test */
    public function putsThePaginatorFromTheServiceIntoTheEvent()
    {
        $paginator = $this->getMockBuilder('Zend\Paginator\Paginator')
                          ->setMethods(array('foo')) // setCache makes problem when creating a mock
                          ->disableOriginalConstructor()
                          ->getMock();

        $this->service->expects($this->any())->method('getPaginator')->will($this->returnValue($paginator));

        $this->event->expects($this->once())->method('setEntities')->with($paginator);

        $feature = new PaginationProvider($this->service);
        $feature->execute($this->event);
    }

    /** @test */
    public function hasPageAsDefaultRouteMatchParam()
    {
        $this->routeMatch->expects($this->once())
                         ->method('getParam')
                         ->with('page')
                         ->will($this->returnValue(5));

        $this->service->expects($this->once())
                      ->method('getPaginator')
                      ->with(5);

        $feature = new PaginationProvider($this->service);
        $feature->execute($this->event);
    }

    /** @test */
    public function mightHaveAnotherRouteMatchParamForGettingThePage()
    {
        $this->routeMatch->expects($this->once())
                         ->method('getParam')
                         ->with('my-page')
                         ->will($this->returnValue(7));

        $this->service->expects($this->once())
                      ->method('getPaginator')
                      ->with(7);

        $feature = new PaginationProvider($this->service, 'my-page');
        $feature->execute($this->event);
    }
}

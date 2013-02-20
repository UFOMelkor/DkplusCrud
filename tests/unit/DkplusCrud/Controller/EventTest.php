<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DkplusCrud\Controller\Controller|\PHPUnit_Framework_MockObject_MockObject */
    protected $controller;

    /** @var \Zend\Http\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    /** @var \Zend\Http\Response|\PHPUnit_Framework_MockObject_MockObject */
    protected $response;

    /** @var Event */
    protected $event;

    protected function setUp()
    {
        $this->request  = $this->getMock('Zend\Http\Request');
        $this->response = $this->getMock('Zend\Http\Response');

        $this->controller = $this->getMock('DkplusCrud\Controller\Controller');
        $this->controller->expects($this->any())
                         ->method('getRequest')
                         ->will($this->returnValue($this->request));
        $this->controller->expects($this->any())
                         ->method('getResponse')
                         ->will($this->returnValue($this->response));

        $this->event = new Event($this->controller);
    }

    /** @test */
    public function providesControllerAccess()
    {
        $this->assertSame($this->controller, $this->event->getController());
    }

    /** @test */
    public function usesTheControllerAlsoAsTarget()
    {
        $this->assertSame($this->controller, $this->event->getTarget());
    }

    /** @test */
    public function providesRequestAccess()
    {
        $this->assertSame($this->request, $this->event->getRequest());
    }

    /** @test */
    public function providesTheRequestAlsoAsParam()
    {
        $this->assertSame($this->request, $this->event->getParam('request'));
    }

    /** @test */
    public function providesResponseAccess()
    {
        $this->assertSame($this->response, $this->event->getResponse());
    }

    /** @test */
    public function mayGetAnotherResponse()
    {
        $response = $this->getMock('Zend\Http\Response');
        $this->event->setResponse($response);
        $this->assertSame($response, $this->event->getResponse());
    }

    /** @test */
    public function providesTheResponseAlsoAsParam()
    {
        $this->assertSame($this->response, $this->event->getParam('response'));

        $newResponse = $this->getMock('Zend\Http\Response');
        $this->event->setResponse($newResponse);
        $this->assertSame($newResponse, $this->event->getParam('response'));
    }

    /** @test */
    public function providesADefaultViewModel()
    {
        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $this->event->getViewModel());
    }

    /** @test */
    public function mayGetAnotherViewModel()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->event->setViewModel($viewModel);
        $this->assertSame($viewModel, $this->event->getViewModel());
    }

    /** @test */
    public function providesTheViewModelAlsoAsParam()
    {
        $newViewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->event->setViewModel($newViewModel);
        $this->assertSame($newViewModel, $this->event->getParam('viewModel'));
    }
}

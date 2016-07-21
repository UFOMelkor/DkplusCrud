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
class AjaxFormSupportTest extends TestCase
{
    /** @var AjaxFormSupport */
    protected $feature;

    /** @var \Zend\Http\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    /** @var \Zend\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $form;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {

        $this->form    = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->request = $this->getMock('Zend\Http\Request');
        $this->event   = $this->getMockBuilder('DkplusCrud\Controller\Event')
                              ->disableOriginalConstructor()
                              ->getMock();
        $this->event->expects($this->any())->method('getForm')->will($this->returnValue($this->form));
        $this->event->expects($this->any())->method('getRequest')->will($this->returnValue($this->request));

        $this->feature = new AjaxFormSupport();
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function attachesItselfToThePostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postCreate');

        $this->feature->attachTo('create', $events);
    }

    /** @test */
    public function doesNothingWhenNoAjaxRequestHasBeenDetected()
    {
        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));

        $this->event->expects($this->never())->method('getViewModel');
        $this->event->expects($this->never())->method('getForm');

        $this->feature->execute($this->event);
    }

    /** @test */
    public function usesQueryDataWhenNoPostRequestHasBeenDetected()
    {
        $queryArrayData = array('foo' => 'bar');
        $queryData      = $this->getMock('Zend\Stdlib\Parameters');
        $queryData->expects($this->any())->method('toArray')->will($this->returnValue($queryArrayData));

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('isPost')->will($this->returnValue(false));
        $this->request->expects($this->any())->method('getQuery')->will($this->returnValue($queryData));

        $viewModel = $this->getMock('Zend\View\Model\JsonModel');
        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->form->expects($this->once())->method('setData')->with($queryArrayData);

        $this->feature->execute($this->event);
    }

    /** @test */
    public function usesPostDataWhenAPostRequestHasBeenDetected()
    {
        $postArrayData = array('foo' => 'bar');
        $postData      = $this->getMock('Zend\Stdlib\Parameters');
        $postData->expects($this->any())->method('toArray')->will($this->returnValue($postArrayData));

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('isPost')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('getPost')->will($this->returnValue($postData));

        $viewModel = $this->getMock('Zend\View\Model\JsonModel');
        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->form->expects($this->once())->method('setData')->with($postArrayData);

        $this->feature->execute($this->event);
    }

    /** @test */
    public function assignsTheFormMessagesWhenAnAjaxRequestHasBeenDetected()
    {
        $postData = $this->getMock('Zend\Stdlib\Parameters');

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('isPost')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('getPost')->will($this->returnValue($postData));

        $messages = array('myElement'=>array('myMessage'));
        $this->form->expects($this->once())->method('isValid');
        $this->form->expects($this->once())->method('getMessages')->will($this->returnValue($messages));

        $viewModel = $this->getMock('Zend\View\Model\JsonModel');
        $viewModel->expects($this->once())->method('setVariables')->with($messages);

        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function injectsAJsonModelWhenNoJsonModelIsAvailable()
    {
        $data = $this->getMock('Zend\Stdlib\Parameters');

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('isPost')->will($this->returnValue(true));
        $this->request->expects($this->any())->method('getPost')->will($this->returnValue($data));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));
        $this->event->expects($this->once())
                    ->method('setViewModel')
                    ->with($this->isInstanceOf('Zend\View\Model\JsonModel'));


        $this->feature->execute($this->event);
    }
}

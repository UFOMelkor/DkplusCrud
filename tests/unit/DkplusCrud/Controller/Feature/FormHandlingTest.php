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
 * @covers DkplusCrud\Controller\Feature\FormHandling
 */
class FormHandlingTest extends TestCase
{
    /** @var \DkplusCrud\Controller\Controller|\PHPUnit_Framework_MockObject_MockObject */
    protected $controller;

    /** @var \Zend\Http\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \DkplusCrud\Controller\Feature\Options\SuccessOptions|\PHPUnit_Framework_MockObject_MockObject */
    protected $options;

    /** @var \Zend\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $form;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var FormHandling */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();

        $identifierElement = $this->getMockForAbstractClass('Zend\Form\ElementInterface');
        $this->form        = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->form->expects($this->any())->method('get')->with('id')->will($this->returnValue($identifierElement));

        $this->request    = $this->getMock('Zend\Http\Request');
        $this->controller = $this->getMock(
            'DkplusCrud\Controller\Controller',
            array('postRedirectGet')
        );

        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $this->event->expects($this->any())->method('getForm')->will($this->returnValue($this->form));
        $this->event->expects($this->any())->method('getRequest')->will($this->returnValue($this->request));
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($this->controller));

        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature = new FormHandling($this->service, $this->options);
    }

    /** @test */
    public function isAControllerFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            $this->feature
        );
    }

    /** @test */
    public function attachesItselfToTheMainEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('update');

        $this->feature->attachTo('update', $events);
    }

    /** @test */
    public function doesNothingWhenAnAjaxRequestHasBeenDetectedAndAjaxShouldNotBeHandledImplicitly()
    {
        $this->event->expects($this->never())->method('getViewModel');
        $this->event->expects($this->never())->method('setViewModel');
        $this->event->expects($this->never())->method('setResponse');

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(true));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function canHandleFormsAlsoWhenAnAjaxRequestHasBeenDetectedIfWished()
    {
        $response = $this->getMock('Zend\Http\Response');
        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue($response));
        $this->event->expects($this->once())->method('setResponse')->with($response);

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(true));

        $this->feature->handleAjaxRequest();
        $this->feature->execute($this->event);
    }

    /** @test */
    public function putsAResponseIntoTheEventWhenPrgReturnsAResponse()
    {
        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));

        $response = $this->getMock('Zend\Http\Response');
        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue($response));

        $this->event->expects($this->once())->method('setResponse')->with($response);

        $this->feature->execute($this->event);
    }

    /** @test */
    public function stopsPropagationWhenPrgReturnsAResponse()
    {
        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));

        $response = $this->getMock('Zend\Http\Response');
        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue($response));

        $this->event->expects($this->once())->method('stopPropagation');

        $this->feature->execute($this->event);
    }

    /** @test */
    public function putsTheFormFromTheEventIntoTheViewModelWhenPrgReturnsFalse()
    {
        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));

        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue(false));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $viewModel->expects($this->once())->method('setVariable')->with('form', $this->form);

        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function putsTheFormFromTheEventIntoTheViewModelWhenPrgReturnsData()
    {
        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));

        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue(array()));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $viewModel->expects($this->once())->method('setVariable')->with('form', $this->form);

        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function putsTheDataIntoTheFormWhenPrgReturnsData()
    {
        $data = array('foo' => 'bar');

        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));
        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue($data));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->form->expects($this->once())->method('setData')->with($data);
        $this->feature->execute($this->event);
    }

    /** @test */
    public function putsTheDataIntoTheUpdateMethodWhenFormIsValidAndAnIdentifierExists()
    {
        $this->ensuresTheFormToBeValid();

        $data = array('foo' => 'bar');

        $this->event->expects($this->any())->method('hasIdentifier')->will($this->returnValue(true));
        $this->event->expects($this->any())->method('getIdentifier')->will($this->returnValue(5));

        $this->form->expects($this->any())->method('isValid')->will($this->returnValue(true));
        $this->form->expects($this->any())->method('getData')->will($this->returnValue($data));

        $this->service->expects($this->once())->method('update')->with($data, 5);

        $this->feature->execute($this->event);
    }

    /**
     * Ensures that we can follow the valid-form-path without any error/exception.
     */
    protected function ensuresTheFormToBeValid()
    {
        $this->request->expects($this->any())->method('isXmlHttpRequest')->will($this->returnValue(false));
        $this->controller->expects($this->any())->method('postRedirectGet')->will($this->returnValue(array()));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $this->form->expects($this->any())->method('isValid')->will($this->returnValue(true));
    }

    /** @test */
    public function putsTheDataIntoTheCreateMethodWhenFormIsValidAndNoIdentifierExists()
    {
        $this->ensuresTheFormToBeValid();

        $data = array('foo' => 'bar');

        $this->event->expects($this->any())->method('hasIdentifier')->will($this->returnValue(false));

        $this->form->expects($this->any())->method('isValid')->will($this->returnValue(true));
        $this->form->expects($this->any())->method('getData')->will($this->returnValue($data));

        $this->service->expects($this->once())->method('create')->with($data);

        $this->feature->execute($this->event);
    }

    /** @test */
    public function putsTheEntityIntoTheEvent()
    {
        $this->ensuresTheFormToBeValid();

        $entity = $this->getMock('stdClass');

        $this->event->expects($this->any())->method('hasIdentifier')->will($this->returnValue(false));

        $this->form->expects($this->any())->method('isValid')->will($this->returnValue(true));
        $this->form->expects($this->any())->method('getData')->will($this->returnValue(array()));

        $this->service->expects($this->any())->method('create')->will($this->returnValue($entity));

        $this->event->expects($this->once())->method('setEntity')->with($entity);

        $this->feature->execute($this->event);
    }
}

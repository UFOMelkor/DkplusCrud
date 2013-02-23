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
        $this->assertSame($this->event->getViewModel(), $this->event->getParam('viewModel'));
    }

    /**
     * @test
     * @expectedException DkplusCrud\Controller\ConfigurationError
     * @expectedExceptionMessage On “preCreate” there should be a parameter “identifier”
     */
    public function throwsAnExceptionOnRequestedButMissingIdentifier()
    {
        $this->event->setName('preCreate');
        $this->event->getIdentifier();
    }

    /** @test */
    public function mayProvideIdentifierAccess()
    {
        $this->event->setIdentifier(7);

        $this->assertSame(7, $this->event->getIdentifier());
    }

    /** @test */
    public function mayProvideAnIdentifierAlsoAsParam()
    {
        $this->event->setIdentifier(10);

        $this->assertSame(10, $this->event->getParam('identifier'));
    }

    /**
     * @test
     * @expectedException DkplusCrud\Controller\ConfigurationError
     * @expectedExceptionMessage On “preCreate” there should be a parameter “entity”
     */
    public function throwsAnExceptionOnRequestedButMissingEntity()
    {
        $this->event->setName('preCreate');
        $this->event->getEntity();
    }

    /** @test */
    public function mayProvideEntityAccess()
    {
        $entity = new \stdClass();
        $this->event->setEntity($entity);

        $this->assertSame($entity, $this->event->getEntity());
    }

    /** @test */
    public function mayProvideAnEntityAlsoAsParam()
    {
        $entity = new \stdClass();
        $this->event->setEntity($entity);

        $this->assertSame($entity, $this->event->getParam('entity'));
    }

    /** @test */
    public function canTestWhetherAnEntityIsAvailable()
    {
        $this->assertFalse($this->event->hasEntity());

        $this->event->setEntity(new \stdClass());

        $this->assertTrue($this->event->hasEntity());
    }

    /**
     * @test
     * @expectedException DkplusCrud\Controller\ConfigurationError
     * @expectedExceptionMessage On “preCreate” there should be a parameter “entities”
     */
    public function throwsAnExceptionOnRequestedButMissingEntities()
    {
        $this->event->setName('preCreate');
        $this->event->getEntities();
    }

    /** @test */
    public function mayProvideEntitiesAccess()
    {
        $entities = array(new \stdClass(), new \stdClass());
        $this->event->setEntities($entities);

        $this->assertSame($entities, $this->event->getEntities());
    }

    /** @test */
    public function mayProvideEntitiesAlsoAsParam()
    {
        $entities = array(new \stdClass(), new \stdClass());
        $this->event->setEntities($entities);

        $this->assertSame($entities, $this->event->getParam('entities'));
    }

    /**
     * @test
     * @expectedException DkplusCrud\Controller\ConfigurationError
     * @expectedExceptionMessage On “preCreate” there should be a parameter “form”
     */
    public function throwsAnExceptionOnRequestedButMissingForm()
    {
        $this->event->setName('preCreate');
        $this->event->getForm();
    }

    /** @test */
    public function mayProvideFormAccess()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->event->setForm($form);

        $this->assertSame($form, $this->event->getForm());
    }

    /** @test */
    public function mayProvideAFormAlsoAsParam()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->event->setForm($form);

        $this->assertSame($form, $this->event->getParam('form'));
    }

    /** @test */
    public function canTestWhetherAFormIsAvailable()
    {
        $this->assertFalse($this->event->hasForm());

        $this->event->setForm($this->getMockForAbstractClass('Zend\Form\FormInterface'));

        $this->assertTrue($this->event->hasForm());
    }

    /** @test */
    public function usesTheDefaultViewModelAsResult()
    {
        $this->assertSame($this->event->getViewModel(), $this->event->getResult());
    }

    /** @test */
    public function canUseACustomViewModelAsResult()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->event->setViewModel($viewModel);

        $this->assertSame($viewModel, $this->event->getResult());
    }

    /** @test */
    public function canUseAResponseAsResult()
    {
        $response = $this->getMock('Zend\Http\Response');
        $this->event->setResponse($response);

        $this->assertSame($response, $this->event->getResult());
    }

    /** @test */
    public function providesTheResultAlsoAsParam()
    {
        $result = $this->event->getResult();
        $param  = $this->event->getParam('__RESULT__');

        \Zend\Debug\Debug::dump($result);
        \Zend\Debug\Debug::dump($param);

        $this->assertSame($result, $param);
    }

    /** @test */
    public function providesParamsAlsoWhenTheyHasNotBeenInitializedBefore()
    {
        $this->assertArrayHasKey('request', $this->event->getParams());
        $this->assertArrayHasKey('response', $this->event->getParams());
        $this->assertArrayHasKey('viewModel', $this->event->getParams());
        $this->assertArrayHasKey('__RESULT__', $this->event->getParams());
    }
}

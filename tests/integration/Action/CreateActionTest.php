<?php
/**
 * @category   DkplusIntegration
 * @package    Crud
 * @subpackage Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Integration\Action;

use DkplusCrud\Controller\Controller;
use DkplusCrud\Controller\Action;
use DkplusCrud\Controller\Feature\Options;
use DkplusCrud\Controller\Feature;

use DkplusCrud\Integration\SetUp\ControllerSetUp;

use DkplusCrud\FormHandler\BindFormHandler;
use DkplusCrud\Service\Service;

use PHPUnit_Framework_TestCase as TestCase;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\InputFilter\Input;
use Zend\Mvc\Router\Http\Segment as SegmentRoute;
use Zend\Session\Container as SessionContainer;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

/**
 * @category   DkplusIntegration
 * @package    Crud
 * @subpackage Action
 * @author     Oskar Bley <oskar@programming-php.net>
 * @coversNothing
 */
class CreateActionTest extends TestCase
{
    /** @var ControllerSetUp */
    protected $setUp;

    /** @var Controller */
    protected $controller;

    /** @var \DkplusCrud\Mapper\MapperInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $mapper;

    protected function setUp()
    {
        $this->setUp = new ControllerSetUp();
        $this->setUp->getRouteMatch()->setParam('action', 'create');
        $this->setUp->addRoute(
            'my-module/success/target',
            new SegmentRoute('/target/:id', array('id' => '[0-9]*'), array())
        );

        $nameInput = new Input('name');
        $nameInput->getValidatorChain()->addByName('StringLength', array('min' => 3));
        $form      = new Form();
        $form->setHydrator(new ObjectPropertyHydrator());
        $form->add(new Element\Hidden('id'));
        $form->add(new Element\Text('name'));
        $form->getInputFilter()->add($nameInput);

        $this->mapper = $this->getMockForAbstractClass('DkplusCrud\Mapper\MapperInterface');
        $formHandler  = new BindFormHandler($form, 'stdClass');
        $service      = new Service($this->mapper, $formHandler);

        $successOptions = new Options\SuccessOptions();
        $successOptions->setMessage(
            function ($entity) {
                return \sprintf('%s created.', \htmlspecialchars($entity->name));
            }
        );
        $successOptions->setRedirectRoute('my-module/success/target');
        $successOptions->setRedirectRouteParams(
            function ($entity) {
                return array('id' => $entity->id);
            }
        );

        $this->controller = new Controller();
        $this->controller->addAction(new Action\DefaultAction('create'));
        $this->controller->addFeature('create', new Feature\CreationFormProvider($service));
        $this->controller->addFeature('create', new Feature\FormHandling($service, $successOptions));
        $this->controller->addFeature('create', new Feature\Rendering('my-module/creation/template'));
        $this->controller->addFeature('create', new Feature\AjaxFormSupport());

        $this->setUp->setUp($this->controller);
    }

    /** @test */
    public function returnsAViewModel()
    {
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel',
            $this->controller->dispatch($this->controller->getRequest())
        );
    }

    /** @test */
    public function rendersTheGivenTemplate()
    {
        $viewModel = $this->controller->dispatch($this->controller->getRequest());
        /* @var $viewModel \Zend\View\Model\ViewModel */
        $this->assertEquals('my-module/creation/template', $viewModel->getTemplate());
    }

    /** @test */
    public function canRenderTheForm()
    {
        $viewModel = $this->controller->dispatch($this->controller->getRequest());
        /* @var $viewModel \Zend\View\Model\ViewModel */
        $this->assertInstanceOf('Zend\Form\FormInterface', $viewModel->getVariable('form'));
    }

    /** @test */
    public function redirectsOnNonAjaxSuccess()
    {
        $container = new SessionContainer('prg_post1');
        $container->post = array('name' => 'foo');
        $this->mapper->expects($this->any())
                     ->method('save')
                     ->with($this->isInstanceOf('stdClass'))
                     ->will(
                         $this->returnCallback(
                             function ($entity) {
                               $entity->id = 5;
                               return $entity;
                             }
                         )
                     );

        $response = $this->controller->dispatch($this->controller->getRequest());
        /* @var $response \Zend\Http\Response */
        $this->assertInstanceOf('Zend\Http\Response', $response);

        $this->assertSame('/target/5', $response->getHeaders()->get('Location')->getUri());
    }

    /** @test */
    public function doesNotRedirectOnValidationError()
    {
        $container = new SessionContainer('prg_post1');
        $container->post = array('name' => 'fo');

        $this->mapper->expects($this->never())
                     ->method('save');

        $result = $this->controller->dispatch($this->controller->getRequest());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    /** @test */
    public function doesNotSaveOnAjaxSuccess()
    {
        $container = new SessionContainer('prg_post1');
        $container->post = array('name' => 'foo');
        $this->mapper->expects($this->never())
                     ->method('save');

        $request = $this->controller->getRequest();
        $request->getHeaders()->addHeaderLine('X_REQUESTED_WITH', 'XMLHttpRequest');

        $this->controller->dispatch($request);
    }

    /** @test */
    public function returnsEmptyFormErrosAsJsonOnAjaxValidationError()
    {
        $container = new SessionContainer('prg_post1');
        $container->post = array('name' => 'foo');

        $request = $this->controller->getRequest();
        $request->getHeaders()->addHeaderLine('X_REQUESTED_WITH', 'XMLHttpRequest');

        $result = $this->controller->dispatch($request);
        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);
    }

    /** @test */
    public function returnsTheFormErrosAsJsonOnAjaxValidationError()
    {
        $request = $this->controller->getRequest();
        /* @var $request Request */
        $request->setMethod(Request::METHOD_POST);
        $request->getPost()->set('name', 'fo');
        $request->getHeaders()->addHeaderLine('X_REQUESTED_WITH', 'XMLHttpRequest');

        $result = $this->controller->dispatch($request);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);
        /* @var $result \Zend\View\Model\JsonModel */
        $this->assertArrayHasKey('name', (array) $result->getVariables());

        $nameMessages = $result->getVariable('name');
        $this->assertInternalType('array', $nameMessages);
        $this->assertArrayHasKey('stringLengthTooShort', $nameMessages);
    }
}

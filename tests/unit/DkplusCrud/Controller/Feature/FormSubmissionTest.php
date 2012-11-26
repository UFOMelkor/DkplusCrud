<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use DkplusControllerDsl\Test\TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Controller\Feature\FormSubmission
 */
class FormSubmissionTest extends TestCase
{
    /** @var Controller */
    protected $controller;

    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \DkplusCrud\Controller\Feature\Options\SuccessOptions|\PHPUnit_Framework_MockObject_MockObject */
    protected $options;

    /** @var FormSubmission */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();
        $this->event      = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->options    = $this->getMockIgnoringConstructor('DkplusCrud\Controller\Feature\Options\SuccessOptions');
        $this->controller = new Controller();
        $this->service    = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature    = new FormSubmission($this->service, $this->options, 'user/edit');
        $this->feature->setController($this->controller);

        $this->setUpController($this->controller);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isCrudListener()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            $this->feature
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsDsl()
    {
        $this->assertDsl($this->feature->execute($this->event));
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function usesTheProvidedForm()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');

        $this->event->expects($this->at(0))
                    ->method('getParam')
                    ->with('form')
                    ->will($this->returnValue($form));

        $this->expectsDslToUseForm($form);
        $this->feature->execute($this->event);
    }

    protected function expectsDslToUseForm($form)
    {
        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('assign'))
                    ->getMock();
        $dsl->expects($this->at(2))
            ->method('__call')
            ->with('use', array($form))
            ->will($this->returnSelf());
        $dsl->expects($this->at(4))
            ->method('assign')
            ->will($this->returnSelf());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function validesFormAgainstPostRedirectGet()
    {
        $this->expectsDslToValidateFormAgainstPostRedirectGet();
        $this->feature->execute($this->event);
    }

    protected function expectsDslToValidateFormAgainstPostRedirectGet()
    {
        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('validate', 'against'))
                    ->getMock();
        $dsl->expects($this->once())
            ->method('validate')
            ->will($this->returnSelf());
        $dsl->expects($this->once())
            ->method('against')
            ->with('postredirectget')
            ->will($this->returnSelf());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function storesDataOnSuccessIntoServiceCreateIfNoIdentifierHasBeenProvided()
    {
        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('onSuccess'))
                    ->getMock();

        $successDsl = $this->expectsDslToStoreDataIntoMethod('create');

        $dsl->expects($this->once())
            ->method('onSuccess')
            ->with($successDsl)
            ->will($this->returnSelf());

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function storesDataOnSuccessIntoServiceUpdateIfAnIdentifierHasBeenProvided()
    {
        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('onSuccess'))
                    ->getMock();

        $successDsl = $this->expectsDslToStoreDataIntoMethod('update', 5);

        $dsl->expects($this->once())
            ->method('onSuccess')
            ->with($successDsl)
            ->will($this->returnSelf());

        $this->event->expects($this->at(1))
                    ->method('getParam')
                    ->with('identifier')
                    ->will($this->returnValue(5));

        $this->feature->execute($this->event);
    }

    /**
     * @param string $serviceMethod
     * @param int|null $additionalArgument
     * @return \DkplusControllerDsl\Dsl\DslInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function expectsDslToStoreDataIntoMethod($serviceMethod, $additionalArgument = null)
    {
        $phrases = array('store', 'formData', 'into');

        if ($additionalArgument !== null) {
            $phrases[] = 'with';
        }

        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases($phrases)
                    ->getMock();
        $dsl->expects($this->once())
            ->method('store')
            ->will($this->returnSelf());
        $dsl->expects($this->once())
            ->method('formData')
            ->will($this->returnSelf());
        $dsl->expects($this->once())
            ->method('into')
            ->with(array($this->service, $serviceMethod))
            ->will($this->returnSelf());

        if ($additionalArgument !== null) {
            $dsl->expects($this->at(3)) //atLeastOnce() does not work here
                ->method('with')
                ->with($additionalArgument)
                ->will($this->returnSelf());
            $dsl->expects($this->any())
                ->method('with')
                ->will($this->returnSelf());

        }
        return $dsl;
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function redirectsOnSuccess()
    {
        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('onSuccess'))
                    ->getMock();

        $this->options->expects($this->any())
                      ->method('getRedirectRoute')
                      ->will($this->returnValue('my-redirect-route'));

        $successDsl = $this->expectsDsl()->toRedirectToRoute(
            'my-redirect-route',
            array($this->options, 'getComputatedRedirectRouteParams')
        );

        $dsl->expects($this->once())
            ->method('onSuccess')
            ->with($successDsl)
            ->will($this->returnSelf());

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @testdox adds a callback as success message
     */
    public function addsCallbackAsSuccessMessage()
    {
        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('onSuccess'))
                    ->getMock();

        $successDsl = $this->expectsDsl()
                           ->toAddFlashMessage(array($this->options, 'getComputatedMessage'), 'success');

        $dsl->expects($this->once())
            ->method('onSuccess')
            ->with($successDsl)
            ->will($this->returnSelf());

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function rendersTheTemplate()
    {
        $this->expectsDsl()->toRender('user/edit');
        $this->feature->execute($this->event);
    }
}

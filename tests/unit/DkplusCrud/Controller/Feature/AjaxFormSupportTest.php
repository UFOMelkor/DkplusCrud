<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use DkplusControllerDsl\Test\TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class AjaxFormSupportTest extends TestCase
{
    /** @var AjaxFormSupport */
    protected $feature;

    /** @var \Zend\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $controller;

    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        $this->event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');

        $this->feature = new AjaxFormSupport();
    }

    /** @return Controller */
    protected function getController()
    {
        if ($this->controller === null) {
            $this->controller = new Controller();
            $this->setUpController($this->controller);
        }
        return $this->controller;
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesItselfAsPostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postCreate');

        $this->feature->attachTo('create', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function assignsTheFormMessagesWhenAnAjaxRequestIsDetected()
    {
        $this->feature->setController($this->getController());

        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('onAjaxRequest'))
                    ->getMock();

        $ajaxDsl = $this->getDslMockBuilder()
                        ->withMockedPhrases(array('assign', 'formMessages'))
                        ->getMock();
        $ajaxDsl->expects($this->once())
                ->method('assign')
                ->will($this->returnSelf());
        $ajaxDsl->expects($this->once())
                ->method('formMessages')
                ->will($this->returnSelf());

        $dsl->expects($this->once())
            ->method('onAjaxRequest')
            ->with($ajaxDsl)
            ->will($this->returnSelf());

        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('result')
                    ->will($this->returnValue($this->getController()->dsl()));

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function assignsAsJsonWhenAnAjaxRequestIsDetected()
    {
        $this->feature->setController($this->getController());

        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('onAjaxRequest'))
                    ->getMock();

        $ajaxDsl = $this->getDslMockBuilder()
                        ->withMockedPhrases(array('asJson'))
                        ->getMock();
        $ajaxDsl->expects($this->once())
                ->method('asJson')
                ->will($this->returnSelf());

        $dsl->expects($this->once())
            ->method('onAjaxRequest')
            ->with($ajaxDsl)
            ->will($this->returnSelf());

        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('result')
                    ->will($this->returnValue($this->getController()->dsl()));

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function validatesTheFormWhenAnAjaxRequestIsDetected()
    {
        $this->feature->setController($this->getController());
        $this->setXmlHttpRequest(true);

        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $form->expects($this->once())
             ->method('isValid');

        $map = array(
            array('form', null, $form),
            array('result', null, $this->getController()->dsl())
        );
        $this->event->expects($this->any())
                    ->method('getParam')
                    ->will($this->returnValueMap($map));

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     */
    public function throwsAnExceptionIfNoDslIsStoredAsResultParameter()
    {
        $this->feature->execute($this->event);
    }
}

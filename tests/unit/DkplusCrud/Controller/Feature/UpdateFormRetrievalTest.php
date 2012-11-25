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
class UpdateFormRetrievalTest extends TestCase
{
    /** @var UpdateForm */
    protected $feature;

    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        $this->event   = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');

        $this->feature = new UpdateFormRetrieval($this->service);
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
    public function attachesItselfAsPreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('preCreate');

        $this->feature->attachTo('create', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsTheFormFromTheService()
    {

        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');

        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('identifier')
                    ->will($this->returnValue(5));

        $this->service->expects($this->any())
                      ->method('getUpdateForm')
                      ->with(5)
                      ->will($this->returnValue($form));

        $this->assertSame($form, $this->feature->execute($this->event));
    }
}

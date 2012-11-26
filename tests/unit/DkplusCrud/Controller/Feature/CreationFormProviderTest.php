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
class CreationFormProviderTest extends TestCase
{
    /** @var CreationFormProvider */
    protected $feature;

    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        $this->event   = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');

        $this->feature = new CreationFormProvider($this->service);
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
    public function returnsTheCreationFormFromTheService()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');

        $this->service->expects($this->any())
                      ->method('getCreationForm')
                      ->will($this->returnValue($form));

        $this->assertSame($form, $this->feature->execute($this->event));
    }
}

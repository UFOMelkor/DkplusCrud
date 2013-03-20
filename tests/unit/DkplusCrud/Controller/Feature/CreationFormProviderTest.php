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
 * @covers DkplusCrud\Controller\Feature\CreationFormProvider
 */
class CreationFormProviderTest extends TestCase
{
    /** @var CreationFormProvider */
    protected $feature;

    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        $this->event   = $this->getMockBuilder('\DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');

        $this->feature = new CreationFormProvider($this->service);
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function attachesItselfToThePreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('preCreate');

        $this->feature->attachTo('create', $events);
    }

    /** @test */
    public function setsTheCreationFormFromTheService()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');

        $this->service->expects($this->any())
                      ->method('getCreationForm')
                      ->will($this->returnValue($form));

        $this->event->expects($this->once())
                    ->method('setForm')
                    ->with($form);

        $this->feature->execute($this->event);
    }
}

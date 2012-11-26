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
class EntityProviderTest extends TestCase
{
    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var EntityProvider */
    protected $feature;

    protected function setUp()
    {
        $this->event   = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature = new EntityProvider($this->service);
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
               ->with('preRead');

        $this->feature->attachTo('read', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function fetchesTheEntityFromTheServiceUsingTheIdentifier()
    {
        $entity = $this->getMock('stdClass');

        $this->event->expects($this->once())
                    ->method('getParam')
                    ->with('identifier')
                    ->will($this->returnValue(5));

        $this->service->expects($this->any())
                      ->method('get')
                      ->with(5)
                      ->will($this->returnValue($entity));

        $this->assertSame($entity, $this->feature->execute($this->event));
    }
}

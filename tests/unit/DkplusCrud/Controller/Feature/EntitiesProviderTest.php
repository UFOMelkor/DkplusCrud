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
class EntitiesProviderTest extends TestCase
{
    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var EntitiesProvider */
    protected $feature;

    protected function setUp()
    {
        $this->event   = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature = new EntitiesProvider($this->service);
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function attachesItselfAsPreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())->method('attach')->with('preRead');

        $this->feature->attachTo('read', $events);
    }

    /** @test */
    public function putsTheEntitiesFromTheServiceIntoTheEvent()
    {
        $collection = $this->getMock('stdClass');

        $this->service->expects($this->any())->method('getAll')->will($this->returnValue($collection));

        $this->event->expects($this->once())->method('setEntities')->with($collection);

        $this->feature->execute($this->event);
    }
}

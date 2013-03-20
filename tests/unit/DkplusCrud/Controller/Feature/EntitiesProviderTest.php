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
 * @covers DkplusCrud\Controller\Feature\EntitiesProvider
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
    public function attachesItselfToThePreEvent()
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

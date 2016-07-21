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
 */
class DeletionTest extends TestCase
{
    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var FormSubmission */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature = new Deletion($this->service);

        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            $this->feature
        );
    }

    /** @test */
    public function attachesItselfToTheMainEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('delete');

        $this->feature->attachTo('delete', $events);
    }

    /** @test */
    public function deletesTheEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->service->expects($this->once())
                      ->method('delete')
                      ->with($entity);

        $this->event->expects($this->any())
                    ->method('getEntity')
                    ->will($this->returnValue($entity));

        $this->feature->execute($this->event);
    }
}

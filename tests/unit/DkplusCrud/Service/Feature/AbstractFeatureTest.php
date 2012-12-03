<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class AbstractFeatureTest extends TestCase
{
    /** @var AbstractFeature */
    protected $feature;

    /** @var \Zend\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $events;

    protected function setUp()
    {
        $this->feature = $this->getMockForAbstractClass('DkplusCrud\Service\Feature\AbstractFeature');
        $this->events  = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function attachesTheExecuteMethodToTheEvents()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isType('string'), array($this->feature, 'execute'));

        $this->feature->attachTo($this->events);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function hasAnDefaultPriorityOfOne()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isType('string'), $this->isType('array'), 1);

        $this->feature->attachTo($this->events);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canGetAnotherPriority()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isType('string'), $this->isType('array'), 10);

        $this->feature->setPriority(10);
        $this->feature->attachTo($this->events);
    }
}

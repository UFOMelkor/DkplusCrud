<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 * @covers DkplusCrud\Service\Feature\AbstractFeature
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

    /** @test */
    public function attachesTheExecuteMethodToTheEvents()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isNull(), array($this->feature, 'execute'));

        $this->feature->attachTo($this->events);
    }

    /** @test */
    public function hasAnDefaultPriorityOfOne()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isNull(), $this->isType('array'), 1);

        $this->feature->attachTo($this->events);
    }

    /** @test */
    public function canGetAnotherPriority()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isNull(), $this->isType('array'), 10);

        $this->feature->setPriority(10);
        $this->feature->attachTo($this->events);
    }
}

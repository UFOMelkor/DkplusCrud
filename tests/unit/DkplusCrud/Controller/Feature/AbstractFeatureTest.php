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
class AbstractFeatureTest extends TestCase
{
    /** @var AbstractFeature */
    protected $feature;

    /** @var \Zend\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $events;

    protected function setUp()
    {
        $this->feature = $this->getMockForAbstractClass('DkplusCrud\Controller\Feature\AbstractFeature');
        $this->events  = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesTheExecuteMethodToTheEvents()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isType('string'), array($this->feature, 'execute'));

        $this->feature->attachTo('foo', $this->events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesTheFeatureToTheEventNameByDefault()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with('foo');

        $this->feature->attachTo('foo', $this->events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function hasAnDefaultPriorityOfOne()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isType('string'), $this->isType('array'), 1);

        $this->feature->attachTo('foo', $this->events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canGetAnotherPriority()
    {
        $this->events->expects($this->once())
                     ->method('attach')
                     ->with($this->isType('string'), $this->isType('array'), 10);

        $this->feature->setPriority(10);
        $this->feature->attachTo('foo', $this->events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesControllerAccess()
    {
        $controller = $this->getMock('DkplusCrud\Controller\Controller');
        $this->feature->setController($controller);
        $this->assertSame($controller, $this->feature->getController());
    }
}

<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use DkplusUnitTest\TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class OrderTest extends TestCase
{
    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $queryBuilder;

    protected function setUp()
    {
        $this->queryBuilder = $this->getMockIgnoringConstructor('Doctrine\ORM\QueryBuilder');
        $this->event        = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('queryBuilder')
                    ->will($this->returnValue($this->queryBuilder));
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function attachesItselfToTheQueryBuilderEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('queryBuilder');

        $feature = new Order('foo');
        $feature->attachTo($events);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function addsAnOrderToTheQuery()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('addOrderBy')
                           ->with('e.foo');

        $feature = new Order('foo');
        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function ordersAscByDefault()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('addOrderBy')
                           ->with($this->isType('string'), 'ASC');

        $feature = new Order('foo');
        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canAlsoOrderDesc()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('addOrderBy')
                           ->with($this->isType('string'), 'DESC');

        $feature = new Order('foo', 'DESC');
        $feature->execute($this->event);
    }
}

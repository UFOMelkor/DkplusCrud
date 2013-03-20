<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 * @covers DkplusCrud\Service\Feature\Order
 */
class OrderTest extends TestCase
{
    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $queryBuilder;

    protected function setUp()
    {
        $this->queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('queryBuilder')
                    ->will($this->returnValue($this->queryBuilder));
    }

    /** @test */
    public function attachesItselfToTheQueryBuilderEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('queryBuilder');

        $feature = new Order('foo');
        $feature->attachTo($events);
    }

    /** @test */
    public function addsAnOrderToTheQuery()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('addOrderBy')
                           ->with('e.foo');

        $feature = new Order('foo');
        $feature->execute($this->event);
    }

    /** @test */
    public function ordersAscByDefault()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('addOrderBy')
                           ->with($this->isType('string'), 'ASC');

        $feature = new Order('foo');
        $feature->execute($this->event);
    }

    /** @test */
    public function canAlsoOrderDesc()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('addOrderBy')
                           ->with($this->isType('string'), 'DESC');

        $feature = new Order('foo', 'DESC');
        $feature->execute($this->event);
    }
}

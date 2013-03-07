<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class FilterTest extends TestCase
{
    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $queryBuilder;

    /** @var \Doctrine\ORM\Query\Expr|\PHPUnit_Framework_MockObject_MockObject */
    protected $expressions;

    /** @var Filter */
    protected $filter;

    protected function setUp()
    {
        $this->expressions = $this->getMock('Doctrine\ORM\Query\Expr');

        $this->queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->queryBuilder->expects($this->any())
                           ->method('expr')
                           ->will($this->returnValue($this->expressions));

        $this->event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('queryBuilder')
                    ->will($this->returnValue($this->queryBuilder));

        $this->filter = new Filter();
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

        $this->filter->attachTo($events);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function enlargesTheResultByDefault()
    {
        $this->expressions->expects($this->any())
                          ->method($this->anything())
                          ->will($this->returnValue($this->getExprMock()));

        $this->queryBuilder->expects($this->once())
                           ->method('orWhere');

        $this->filter->equals('foo', 5);
        $this->filter->execute($this->event);
    }

    /** @return \Doctrine\ORM\Query\Expr\Base|\PHPUnit_Framework_MockObject_MockObject */
    protected function getExprMock()
    {
        return $this->getMockForAbstractClass('Doctrine\ORM\Query\Expr\Base');
    }


    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function mayAlsoRefineTheResult()
    {
        $this->expressions->expects($this->any())
                          ->method($this->anything())
                          ->will($this->returnValue($this->getExprMock()));

        $this->queryBuilder->expects($this->once())
                           ->method('andWhere');

        $this->filter->refineResults();
        $this->filter->equals('foo', 5);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function usesTheLastRefineEnlargeBeforeExecuting()
    {
        $this->expressions->expects($this->any())
                          ->method($this->anything())
                          ->will($this->returnValue($this->getExprMock()));

        $this->queryBuilder->expects($this->once())
                           ->method('orWhere');

        $this->filter->refineResults();
        $this->filter->equals('foo', 5);
        $this->filter->enlargeResults();
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterEquals()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('eq')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', 6);
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->equals('foo', 6);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterBetween()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('between')
                          ->with('e.foo', ':foo0', ':foo1')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->at(3))
                           ->method('setParameter')
                           ->with('foo0', 6);
        $this->queryBuilder->expects($this->at(4))
                           ->method('setParameter')
                           ->with('foo1', 7);
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->between('foo', 6, 7);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterLike()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('like')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', 'bar%');
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->like('foo', 'bar%');
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterInArray()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('in')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', array('foo', 'bar', 'baz'));
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->inArray('foo', array('foo', 'bar', 'baz'));
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterGreaterThanEquals()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('gte')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', 20);
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->greaterThanEquals('foo', 20);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterGreaterThan()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('gt')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', 20);
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->greaterThan('foo', 20);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterLessThanEquals()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('lte')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', 20);
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->lessThanEquals('foo', 20);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterLessThan()
    {
        $expression = $this->getExprMock();

        $this->expressions->expects($this->any())
                          ->method('lt')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expression));

        $this->queryBuilder->expects($this->once())
                           ->method('setParameter')
                           ->with('foo0', 20);
        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($expression);

        $this->filter->lessThan('foo', 20);
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function canFilterByMultipleCriterias()
    {
        $expressionA = $this->getExprMock();
        $expressionB = $this->getExprMock();

        $this->expressions->expects($this->at(0))
                          ->method('eq')
                          ->with('e.foo', ':foo0')
                          ->will($this->returnValue($expressionA));
        $this->expressions->expects($this->at(1))
                          ->method('like')
                          ->with('e.bar', ':bar1')
                          ->will($this->returnValue($expressionB));

        $this->queryBuilder->expects($this->at(6))
                           ->method('setParameter')
                           ->with('foo0', 20);
        $this->queryBuilder->expects($this->at(7))
                           ->method('setParameter')
                           ->with('bar1', 'ba%');

        $this->queryBuilder->expects($this->at(4))
                           ->method('orWhere')
                           ->with($expressionA);
        $this->queryBuilder->expects($this->at(5))
                           ->method('orWhere')
                           ->with($expressionB);

        $this->filter->equals('foo', 20);
        $this->filter->like('bar', 'ba%');
        $this->filter->execute($this->event);
    }
}

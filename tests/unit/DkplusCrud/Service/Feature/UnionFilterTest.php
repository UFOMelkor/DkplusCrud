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
class UnionFilterTest extends TestCase
{
    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $queryBuilder;

    /** @var \Doctrine\ORM\Query\Expr|\PHPUnit_Framework_MockObject_MockObject */
    protected $expressions;

    /** @var \Doctrine\ORM\Query\Expr|\PHPUnit_Framework_MockObject_MockObject */
    protected $expression;

    protected function setUp()
    {
        $this->expression = $this->getMockForAbstractClass('Doctrine\ORM\Query\Expr\Base');

        $this->expressions  = $this->getMockIgnoringConstructor('Doctrine\ORM\Query\Expr');
        $this->queryBuilder = $this->getMockIgnoringConstructor('Doctrine\ORM\QueryBuilder');
        $this->queryBuilder->expects($this->any())
                           ->method('expr')
                           ->will($this->returnValue($this->expressions));
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
    public function extendsTheIntersectionFilter()
    {
        $this->assertInstanceOf('DkplusCrud\Service\Feature\IntersectionFilter', new UnionFilter('eq', 'attr', 5));
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

        $feature = new UnionFilter('eq', 'attr', 5);
        $feature->attachTo($events);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function addsAnUnionCondition()
    {
        $this->expressions->expects($this->any())
                          ->method($this->anything())
                          ->will($this->returnValue($this->expression));

        $this->queryBuilder->expects($this->once())
                           ->method('orWhere')
                           ->with($this->isInstanceOf('Doctrine\ORM\Query\Expr\Base'));

        $feature = new UnionFilter('eq', 'attr', 5);
        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     * @dataProvider singleValueConditions
     */
    public function canAddDifferentTypesOfConditionWithOneValue($conditionType, $value)
    {
        $attribute = 'foo';
        $this->expressions->expects($this->once())
                          ->method($conditionType)
                          ->with('e.' . $attribute, $value)
                          ->will($this->returnValue($this->expression));

        $this->queryBuilder->expects($this->once())
                           ->method('andWhere')
                           ->with($this->isInstanceOf('Doctrine\ORM\Query\Expr\Base'));

        $feature = new IntersectionFilter($conditionType, $attribute, $value);
        $feature->execute($this->event);
    }
}

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
class EntityFilterTest extends TestCase
{
    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $queryBuilder;

    /** @var EntityFilter */
    protected $filter;

    protected function setUp()
    {
        $this->queryBuilder = $this->getMockIgnoringConstructor('Doctrine\ORM\QueryBuilder');

        $this->event        = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('queryBuilder')
                    ->will($this->returnValue($this->queryBuilder));

        $this->filter = new EntityFilter('My\Entity');
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
    public function refinesTheResultByDefault()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('andWhere');

        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function mayAlsoEnlargeTheResult()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('andWhere');

        $this->filter->refineResults();
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function usesTheLastRefineEnlargeBeforeExecuting()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('andWhere');

        $this->filter->enlargeResults();
        $this->filter->refineResults();
        $this->filter->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function filtersEntitiesOfTheGivenClass()
    {
        $this->queryBuilder->expects($this->once())
                           ->method('andWhere')
                           ->with('e INSTANCE OF My\Entity');
        $this->filter->execute($this->event);
    }
}

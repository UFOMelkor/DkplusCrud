<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusUnitTest\TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Controller\Feature\SingleInputFilter
 */
class SingleInputFilterTest extends TestCase
{
    /** @var \DkplusCrud\Service\Service */
    protected $service;

    /** @var \Zend\EventManager\EventInterface */
    protected $event;

    protected function setUp()
    {
        parent::setUp();
        $this->event   = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->service = $this->getMockIgnoringConstructor('DkplusCrud\Service\Service');
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isAFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            new SingleInputFilter($this->service, array('foo'), 'q')
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesItselfAsPreEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('preList');

        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->attachTo('list', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesItselfWithAnHigherPriority()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with($this->isType('string'), $this->isType('array'), 2);

        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->attachTo('list', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function addsAFilterToTheService()
    {
        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $filter = $feature->getFilter();

        $this->service->expects($this->once())
                      ->method('addFeature')
                      ->with($filter);

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canOverrideTheDefaultFilter()
    {
        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');
        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setFilter($filter);

        $this->service->expects($this->once())
                      ->method('addFeature')
                      ->with($filter);

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canUseARefiningFilter()
    {
        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');
        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setFilter($filter);

        $filter->expects($this->once())
               ->method('refineResults');

        $feature->refineResults();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canUseAnEnlargingFilter()
    {
        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');
        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setFilter($filter);

        $filter->expects($this->once())
               ->method('enlargeResults');

        $feature->enlargeResults();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canFilterForASingleGivenValues()
    {
        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');
        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setFilter($filter);

        $filter->expects($this->once())
               ->method('like')
               ->with('foo', 'q');

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canFilterByMultipleProperties()
    {
        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');
        $feature = new SingleInputFilter($this->service, array('foo', 'bar'), 'q');
        $feature->setFilter($filter);

        $filter->expects($this->at(0))
               ->method('like')
               ->with('foo', 'q');
        $filter->expects($this->at(1))
               ->method('like')
               ->with('bar', 'q');

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @dataProvider filteringComparators
     */
    public function canFilterByDifferentComparators($type, $method, $value)
    {
        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');
        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setFilter($filter);

        $feature->setComparator($type);

        $filter->expects($this->once())
               ->method($method)
               ->with('foo', $value);

        $feature->execute($this->event);
    }

    public static function filteringComparators()
    {
        return array(
            array(SingleInputFilter::FILTER_LIKE, 'like', 'q'),
            array(SingleInputFilter::FILTER_CONTAINING, 'like', '%q%'),
            array(SingleInputFilter::FILTER_STARTING_WITH, 'like', 'q%'),
            array(SingleInputFilter::FILTER_ENDING_WITH, 'like', '%q'),
            array(SingleInputFilter::FILTER_EQUALS, 'equals', 'q'),
            array(SingleInputFilter::FILTER_GREATER_THAN_EQUALS, 'greaterThanEquals', 'q'),
            array(SingleInputFilter::FILTER_GREATER_THAN, 'greaterThan', 'q'),
            array(SingleInputFilter::FILTER_LESS_THAN_EQUALS, 'lessThanEquals', 'q'),
            array(SingleInputFilter::FILTER_LESS_THAN, 'lessThan', 'q'),
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canGetTheValuesFromTheRouter()
    {
        $params = $this->getMock('stdClass', array('fromRoute'));
        $params->expects($this->any())
               ->method('fromRoute')
               ->with('q')
               ->will($this->returnValue('bar'));

        $controller = $this->getMock('DkplusCrud\Controller\Controller', array('params'));
        $controller->expects($this->any())
                   ->method('params')
                   ->will($this->returnValue($params));

        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');

        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setSource(SingleInputFilter::SOURCE_ROUTE);
        $feature->setFilter($filter);
        $feature->setController($controller);

        $filter->expects($this->once())
               ->method('like')
               ->with('foo', 'bar');

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canGetTheValuesFromQuery()
    {
        $params = $this->getMock('stdClass', array('fromQuery'));
        $params->expects($this->any())
               ->method('fromQuery')
               ->with('q')
               ->will($this->returnValue('bar'));

        $controller = $this->getMock('DkplusCrud\Controller\Controller', array('params'));
        $controller->expects($this->any())
                   ->method('params')
                   ->will($this->returnValue($params));

        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');

        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setSource(SingleInputFilter::SOURCE_QUERY);
        $feature->setFilter($filter);
        $feature->setController($controller);

        $filter->expects($this->once())
               ->method('like')
               ->with('foo', 'bar');

        $feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canGetTheValuesFromPost()
    {
        $params = $this->getMock('stdClass', array('fromPost'));
        $params->expects($this->any())
               ->method('fromPost')
               ->with('q')
               ->will($this->returnValue('bar'));

        $controller = $this->getMock('DkplusCrud\Controller\Controller', array('params'));
        $controller->expects($this->any())
                   ->method('params')
                   ->will($this->returnValue($params));

        $filter  = $this->getMock('DkplusCrud\Service\Feature\Filter');

        $feature = new SingleInputFilter($this->service, array('foo'), 'q');
        $feature->setSource(SingleInputFilter::SOURCE_POST);
        $feature->setFilter($filter);
        $feature->setController($controller);

        $filter->expects($this->once())
               ->method('like')
               ->with('foo', 'bar');

        $feature->execute($this->event);
    }
}

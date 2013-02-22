<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class AbstractActionTest extends TestCase
{
    /** @var string */
    const ACTION_NAME = 'paginate';

    /** @var AbstractAction */
    protected $action;

    protected function setUp()
    {
        $this->action = $this->getMockForAbstractClass(
            'DkplusCrud\Controller\Action\AbstractAction',
            array(self::ACTION_NAME)
        );
    }

    /** @test */
    public function providesAName()
    {
        $this->assertEquals(self::ACTION_NAME, $this->action->getName());
    }

    /** @test */
    public function putsTheControllerIntoEachFeature()
    {
        $controller = $this->getMock('DkplusCrud\Controller\Controller');
        $events     = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $feature    = $this->getMockForAbstractClass('DkplusCrud\Controller\Feature\FeatureInterface');
        $feature->expects($this->once())
                ->method('setController')
                ->with($controller);

        $this->action->setController($controller);

        $this->action->addFeature($feature);
        $this->action->attachTo($events);
    }

    /** @test */
    public function attachesTheEventsToEachFeature()
    {
        $controller = $this->getMock('DkplusCrud\Controller\Controller');
        $events     = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');

        $feature = $this->getMockForAbstractClass('DkplusCrud\Controller\Feature\FeatureInterface');
        $feature->expects($this->once())
                ->method('attachTo')
                ->with($this->isType('string'), $events);

        $this->action->setController($controller);

        $this->action->addFeature($feature);
        $this->action->attachTo($events);
    }

    /** @test */
    public function attachesTheActionNameAsEventNameToEachFeature()
    {
        $controller = $this->getMock('DkplusCrud\Controller\Controller');
        $events     = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');

        $feature = $this->getMockForAbstractClass('DkplusCrud\Controller\Feature\FeatureInterface');
        $feature->expects($this->once())
                ->method('attachTo')
                ->with(self::ACTION_NAME);

        $this->action->setController($controller);

        $this->action->addFeature($feature);
        $this->action->attachTo($events);
    }

    /** @test */
    public function providesADefaultEvent()
    {
        $this->action->setController($this->getMock('DkplusCrud\Controller\Controller'));
        $this->assertInstanceOf('DkplusCrud\Controller\Event', $this->action->getEvent());
    }

    /** @test */
    public function mayGetACustomEvent()
    {
        $event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                      ->disableOriginalConstructor()
                      ->getMock();

        $this->action->setEvent($event);

        $this->assertInstanceOf('DkplusCrud\Controller\Event', $this->action->getEvent());
    }

    /**
     * @test
     * @expectedException DkplusCrud\Controller\Action\Exception\RuntimeException
     * @expectedExceptionMessage Could not provide a default event because no controller has been injected
     */
    public function cannotProvideADefaultEventWhenNoControllerHasBeenInjected()
    {
        $this->action->getEvent();
    }
}

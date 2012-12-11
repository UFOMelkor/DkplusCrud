<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use DkplusControllerDsl\Test\TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Controller\Feature\Assigning
 */
class AssigningTest extends TestCase
{
    /** @var Controller */
    protected $controller;

    /** @var Assigning */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = new Controller();
        $this->feature    = new Assigning('data', 'paginator');
        $this->feature->setController($this->controller);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function attachesItselfAsPostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postList');

        $this->feature->attachTo('list', $events);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function assignsTheEventParameterAsAlias()
    {
        $this->setUpController($this->controller);

        $paginator = $this->getMockIgnoringConstructor('Zend\Paginator\Paginator');

        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('assign'))
                    ->getMock();
        $dsl->expects($this->at(0))
            ->method('assign')
            ->with($paginator)
            ->will($this->returnSelf());
        $dsl->expects($this->at(1))
            ->method('__call')
            ->with('as', array('paginator'))
            ->will($this->returnSelf());

        $map = array(
            array('result', null, $dsl),
            array('data', null, $paginator)
        );
        $event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $event->expects($this->any())
              ->method('getParam')
              ->will($this->returnValueMap($map));

        $this->assertSame($dsl, $event->getParam('result'));
        $this->assertSame($paginator, $event->getParam('data'));

        $this->feature->execute($event);
    }
}

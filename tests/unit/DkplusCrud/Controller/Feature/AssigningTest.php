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
        $this->feature    = new Assigning('data', 'paginator', 'crud/controller/read');
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
    public function returnsADsl()
    {
        $this->setUpController($this->controller);

        $event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->assertDsl($this->feature->execute($event));
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

        $event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $event->expects($this->any())
              ->method('getParam')
              ->with('paginator')
              ->will($this->returnValue($paginator));

        $dsl = $this->getDslMockBuilder()
                    ->withMockedPhrases(array('assign'))
                    ->getMock();
        $dsl->expects($this->at(0))
            ->method('assign')
            ->with($paginator)
            ->will($this->returnSelf());
        $dsl->expects($this->at(1))
            ->method('__call')
            ->with('as', array('data'))
            ->will($this->returnSelf());

        $this->feature->execute($event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function rendersTheTemplate()
    {
        $this->setUpController($this->controller);

        $event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');

        $this->expectsDsl()->toRender('crud/controller/read');
        $this->feature->execute($event);
    }
}

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
 * @covers     DkplusCrud\Controller\Feature\Rendering
 */
class RenderingTest extends TestCase
{
    /** @var Controller */
    protected $controller;

    /** @var Assigning */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = new Controller();
        $this->feature    = new Rendering('crud/controller/read');
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
    public function rendersTheTemplate()
    {
        $this->setUpController($this->controller);

        $event = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');

        $this->expectsDsl()->toRender('crud/controller/read');
        $this->feature->execute($event);
    }
}

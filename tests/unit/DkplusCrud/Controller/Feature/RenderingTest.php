<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class RenderingTest extends TestCase
{
    /** @var Assigning */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();

        $this->feature = new Rendering('crud/controller/read');
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function setsATemplate()
    {
        $viewModel = $this->getMock('Zend\View\Model\ViewModel');
        $viewModel->expects($this->once())
                  ->method('setTemplate')
                  ->with('crud/controller/read');

        $event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->once())
              ->method('getViewModel')
              ->will($this->returnValue($viewModel));

        $this->feature->execute($event);
    }
}

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
class AssigningTest extends TestCase
{
    /** @var Controller */
    protected $controller;

    /** @var Assigning */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();
        $this->feature = new Assigning('entities', 'paginator');
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function attachesItselfAsPostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postList');

        $this->feature->attachTo('list', $events);
    }

    /** @test */
    public function assignsTheEventParameterAsAlias()
    {
        $paginator = $this->getMockBuilder('Zend\Paginator\Paginator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $viewModel->expects($this->once())
                  ->method('setVariable')
                  ->with('paginator', $paginator);

        $event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->any())
              ->method('getParam')
              ->with('entities')
              ->will($this->returnValue($paginator));
        $event->expects($this->any())
              ->method('getViewModel')
              ->will($this->returnValue($viewModel));

        $this->feature->execute($event);
    }
}

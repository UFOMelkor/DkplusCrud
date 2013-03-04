<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use \PHPUnit_Framework_TestCase as TestCase;

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

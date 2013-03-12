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
    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', new Assigning('value', 'alias'));
    }

    /** @test */
    public function attachesItselfAsPostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postList');

        $feature = new Assigning('value', 'alias');
        $feature->attachTo('list', $events);
    }

    /** @test */
    public function assignsTheEventParameterAsAlias()
    {
        $paginator = $this->getMockBuilder('Zend\Paginator\Paginator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $viewModel->expects($this->once())->method('setVariable')->with('paginator', $paginator);

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

        $feature = new Assigning('entities', 'paginator');
        $feature->execute($event);
    }

    /** @test */
    public function canAlsoAssignValuesDirectly()
    {
        $message = 'foo bar';

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $viewModel->expects($this->once())->method('setVariable')->with('message', $message);

        $event = $this->getMockBuilder('DkplusCrud\Controller\Event')->disableOriginalConstructor()->getMock();
        $event->expects($this->any())->method('getViewModel')->will($this->returnValue($viewModel));

        $feature = new Assigning($message, 'message');
        $feature->useEvent(false);
        $feature->execute($event);
    }
}

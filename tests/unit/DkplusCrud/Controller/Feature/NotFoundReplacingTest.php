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
 * @covers DkplusCrud\Controller\Feature\NotFoundReplacing
 */
class NotFoundReplacingTest extends TestCase
{
    /** @var \Dkplus\Crud\Controller\Controller|\PHPUnit_Framework_MockObject_MockObject */
    protected $controller;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        parent::setUp();
        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->controller = $this->getMock(
            'DkplusCrud\Controller\Controller',
            array('notFoundForward')
        );
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($this->controller));
    }

    /** @test */
    public function isAFeature()
    {
        $feature = new NotFoundReplacing('Application\Controller\Index');
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $feature);
    }

    /** @test */
    public function attachesItselfToTheNotFoundEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('notFoundRead');

        $feature = new NotFoundReplacing('crud/controller/read');
        $feature->attachTo('read', $events);
    }

    /** @test */
    public function replacesContentWithAnotherControllerAction()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->expectsNotFoundForwardToReturnAViewModel($viewModel);

        $this->event->expects($this->once())->method('setViewModel')->with($viewModel);

        $feature = new NotFoundReplacing('Application\Controller\Index');
        $feature->execute($this->event);
    }

    protected function expectsNotFoundForwardToReturnAViewModel($viewModel = null)
    {
        if (!$viewModel) {
            $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        }

        $notFoundForward = $this->getMockBuilder('DkplusBase\Mvc\Controller\Plugin\NotFoundForward')
                                ->disableOriginalConstructor()
                                ->getMock();
        $notFoundForward->expects($this->any())->method('dispatch')->will($this->returnValue($viewModel));

        $this->controller->expects($this->any())->method('notFoundForward')->will($this->returnValue($notFoundForward));
    }

    /** @test */
    public function canConfigurateNotFoundForward()
    {
        $controller  = 'my-controller';
        $routeParams = array('action' => 'my-action');
        $routeName   = 'home';

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $notFoundForward = $this->getMockBuilder('DkplusBase\Mvc\Controller\Plugin\NotFoundForward')
                                ->disableOriginalConstructor()
                                ->getMock();
        $notFoundForward->expects($this->once())
                        ->method('dispatch')
                        ->with($controller, $routeParams, $routeName)
                        ->will($this->returnValue($viewModel));

        $this->controller->expects($this->any())->method('notFoundForward')->will($this->returnValue($notFoundForward));

        $feature = new NotFoundReplacing($controller, $routeParams, $routeName);
        $feature->execute($this->event);
    }

    /** @test */
    public function mustOnlyBeConfiguratedWithAController()
    {
        $controller = 'my-controller';

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $notFoundForward = $this->getMockBuilder('DkplusBase\Mvc\Controller\Plugin\NotFoundForward')
                                ->disableOriginalConstructor()
                                ->getMock();
        $notFoundForward->expects($this->once())
                        ->method('dispatch')
                        ->with($controller, $this->isNull(), $this->isNull())
                        ->will($this->returnValue($viewModel));

        $this->controller->expects($this->any())->method('notFoundForward')->will($this->returnValue($notFoundForward));

        $feature = new NotFoundReplacing($controller);
        $feature->execute($this->event);
    }
}

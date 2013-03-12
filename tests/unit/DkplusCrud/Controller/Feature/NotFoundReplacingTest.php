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
class NotFoundReplacingTest extends TestCase
{
    /** @var \Dkplus\Crud\Controller\Controller|\PHPUnit_Framework_MockObject_MockObject */
    protected $controller;

    /** @var NotFoundReplacing */
    protected $feature;

    /** @var Options\NotFoundOptions|\PHPUnit_Framework_MockObject_MockObject */
    protected $options;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        parent::setUp();
        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->options = $this->getMockBuilder('DkplusCrud\Controller\Feature\Options\NotFoundReplaceOptions')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->controller = $this->getMock(
            'DkplusCrud\Controller\Controller',
            array('flashMessenger', 'notFoundForward')
        );
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($this->controller));

        $this->feature = new NotFoundReplacing($this->options);
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function replacesContentWithAnotherControllerAction()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->expectsNotFoundForwardToReturnAViewModel($viewModel);

        $this->event->expects($this->once())->method('setViewModel')->with($viewModel);

        $this->feature->execute($this->event);
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

        $this->options->expects($this->any())
                      ->method('getContentReplaceController')
                      ->will($this->returnValue($controller));
        $this->options->expects($this->any())
                      ->method('getContentReplaceRouteParams')
                      ->will($this->returnValue($routeParams));
        $this->options->expects($this->any())
                      ->method('getContentReplaceRoute')
                      ->will($this->returnValue($routeName));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $notFoundForward = $this->getMockBuilder('DkplusBase\Mvc\Controller\Plugin\NotFoundForward')
                                ->disableOriginalConstructor()
                                ->getMock();
        $notFoundForward->expects($this->once())
                        ->method('dispatch')
                        ->with($controller, $routeParams, $routeName)
                        ->will($this->returnValue($viewModel));

        $this->controller->expects($this->any())->method('notFoundForward')->will($this->returnValue($notFoundForward));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function addsNo404NotFoundMessageUntilItIsConfigurated()
    {
        $this->controller->expects($this->never())->method('flashMessenger');
        $this->expectsNotFoundForwardToReturnAViewModel();

        $this->feature->execute($this->event);
    }

    /** @test */
    public function canConfigurateA404NotFoundMessage()
    {
        $namespace = 'notFound';
        $message   = 'could not found any data';

        $this->options->expects($this->any())->method('hasErrorMessage')->will($this->returnValue(true));
        $this->options->expects($this->any())->method('getErrorMessage')->will($this->returnValue($message));
        $this->options->expects($this->any())->method('getMessageNamespace')->will($this->returnValue($namespace));

        $flashMessenger = $this->getMock('Zend\Mvc\Controller\Plugin\FlashMessenger');
        $flashMessenger->expects($this->at(0))->method('setNamespace')->with($namespace);
        $flashMessenger->expects($this->at(1))->method('addMessage')->with($message);

        $this->controller->expects($this->any())->method('flashMessenger')->will($this->returnValue($flashMessenger));

        $this->expectsNotFoundForwardToReturnAViewModel();

        $this->feature->execute($this->event);
    }
}

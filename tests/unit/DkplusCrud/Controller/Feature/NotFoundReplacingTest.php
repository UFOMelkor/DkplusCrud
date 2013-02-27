<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Controller\Feature\NotFoundReplacing
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

        $this->controller = $this->getMock('DkplusCrud\Controller\Controller', array('flashMessenger', 'forward'));
        $this->event->expects($this->any())->method('getController')->will($this->returnValue($this->controller));

        $this->feature    = new NotFoundReplacing($this->options);
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
    public function replacesContentWithAnotherControllerAction()
    {
        $this->expectsDsl()->toReplaceContentWithControllerAction();

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function setsAn404ResponseHeaderButIgnoresZfErrorHandling()
    {
        $this->expectsDsl()->toMarkPageAsNotFound(true);

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canConfigurateControllerActionForContentReplacing()
    {
        $controller  = 'my-controller';
        $action      = 'my-action';
        $routeParams = array('my-route' => 'params');

        $this->options->expects($this->any())
             ->method('getContentReplaceController')
             ->will($this->returnValue($controller));
        $this->options->expects($this->any())
             ->method('getContentReplaceAction')
             ->will($this->returnValue($action));
        $this->options->expects($this->any())
             ->method('getContentReplaceRouteParams')
             ->will($this->returnValue($routeParams));

        $this->expectsDsl()->toReplaceContentWithControllerAction($controller, $action, $routeParams);

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function addsNo404NotFoundMessageUntilItIsConfigurated()
    {
        $this->expectsDsl()->toDoNotAddFlashMessages();

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canConfigurateA404NotFoundMessage()
    {
        $message   = 'could not found any data';

        $this->options->expects($this->any())
             ->method('hasErrorMessage')
             ->will($this->returnValue(true));
        $this->options->expects($this->any())
             ->method('getErrorMessage')
             ->will($this->returnValue($message));

        $this->expectsDsl()->toAddFlashMessage($message, 'notFound');

        $this->feature->execute($this->event);
    }
}

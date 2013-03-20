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
 * @covers DkplusCrud\Controller\Feature\AjaxLayoutDisabling
 */
class AjaxLayoutDisablingTest extends TestCase
{
    /** @var AjaxLayoutDisabling */
    protected $feature;

    /** @var \Zend\EventManager\EventInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        $this->event = $this->getMockBuilder('DkplusCrud\Controller\Event')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->feature = new AjaxLayoutDisabling();
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf('DkplusCrud\Controller\Feature\FeatureInterface', $this->feature);
    }

    /** @test */
    public function attachesItselfToThePostEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with('postList');

        $this->feature->attachTo('list', $events);
    }

    /** @test */
    public function disablesTheLayoutWhenAnAjaxRequestIsDetected()
    {
        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->any())
                ->method('isXmlHttpRequest')
                ->will($this->returnValue(true));

        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $viewModel->expects($this->once())
                  ->method('setTerminal')
                  ->with(true);

        $this->event->expects($this->any())
                    ->method('getRequest')
                    ->will($this->returnValue($request));
        $this->event->expects($this->any())
                    ->method('getViewModel')
                    ->will($this->returnValue($viewModel));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function doesNotDisableTheLayoutWhenNoAjaxRequestIsDetected()
    {
        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->any())
                ->method('isXmlHttpRequest')
                ->will($this->returnValue(false));

        $this->event->expects($this->any())
                    ->method('getRequest')
                    ->will($this->returnValue($request));
        $this->event->expects($this->never())
                    ->method('getViewModel');

        $this->feature->execute($this->event);
    }
}

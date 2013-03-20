<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 * @covers DkplusCrud\Controller\Action\FormAction
 */
class FormActionTest extends ActionTestCase
{
    protected function setUp()
    {
        $this->actionName = 'update';
        $this->action     = new FormAction($this->actionName);
        parent::setUp();
    }

    /** @test */
    public function triggersPreEvent()
    {
        $this->expectPreEventToBeTriggered();
        $this->action->execute();
    }

    /** @test */
    public function triggersNotFoundEventWhenNoEntityHasBeenFound()
    {
        $this->event->expects($this->any())
                    ->method('hasForm')
                    ->will($this->returnValue(false));

        $this->expectNotFoundEventToBeTriggered();
        $this->expectCountOfTriggeredEvents(2);

        $this->action->execute();
    }

    /** @test */
    public function triggersMainEventWhenAnEntityHasBeenFound()
    {
        $this->event->expects($this->any())
                    ->method('hasForm')
                    ->will($this->returnValue(true));

        $this->expectMainEventToBeTriggered();
        $this->expectCountOfTriggeredEvents(3);

        $this->action->execute();
    }

    /** @test */
    public function triggersPostEventWhenAnEntityHasBeenFound()
    {
        $this->event->expects($this->any())
                    ->method('hasForm')
                    ->will($this->returnValue(true));

        $this->expectPostEventToBeTriggered();
        $this->expectCountOfTriggeredEvents(3);

        $this->action->execute();
    }

    /** @test */
    public function returnsEventResultWhenAnEntityHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->event->expects($this->any())
                    ->method('hasForm')
                    ->will($this->returnValue(true));

        $this->event->expects($this->any())
                    ->method('getResult')
                    ->will($this->returnValue($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }

    /** @test */
    public function returnsEventResultWhenNoEntityHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->event->expects($this->any())
                    ->method('hasForm')
                    ->will($this->returnValue(false));

        $this->event->expects($this->any())
                    ->method('getResult')
                    ->will($this->returnValue($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }
}

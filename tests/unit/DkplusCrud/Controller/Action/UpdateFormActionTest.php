<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class UpdateFormActionTest extends ActionTestCase
{
    protected function setUp()
    {
        $this->actionName = 'update';
        $this->action     = new UpdateFormAction($this->actionName);
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

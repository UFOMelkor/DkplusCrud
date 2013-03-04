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
class DefaultActionTest extends ActionTestCase
{
    protected function setUp()
    {
        $this->actionName = 'create';
        $this->action     = new DefaultAction($this->actionName);
        parent::setUp();
    }

    /** @test */
    public function triggersPreEvent()
    {
        $this->expectPreEventToBeTriggered();
        $this->action->execute();
    }

    /** @test */
    public function triggersMainEvent()
    {
        $this->expectMainEventToBeTriggered();
        $this->action->execute();
    }

    /** @test */
    public function triggersPostEvent()
    {
        $this->expectPostEventToBeTriggered();
        $this->action->execute();
    }

    /** @test */
    public function returnsEventResult()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->event->expects($this->any())
                    ->method('getResult')
                    ->will($this->returnValue($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }
}

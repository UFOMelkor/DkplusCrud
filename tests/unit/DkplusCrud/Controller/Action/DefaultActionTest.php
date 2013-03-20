<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 * @covers DkplusCrud\Controller\Action\DefaultAction
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

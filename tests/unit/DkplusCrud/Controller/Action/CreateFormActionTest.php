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
class CreateFormActionTest extends ActionTestCase
{
    protected function setUp()
    {
        $this->actionName = 'create';
        $this->action     = new CreateFormAction($this->actionName);
        parent::setUp();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isInitiallyStrict()
    {
        $this->assertTrue($this->action->isStrict());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canAlsoBeNotStrict()
    {
        $this->action->setStrict(false);
        $this->assertFalse($this->action->isStrict());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersPreEventToGetTheEntity()
    {
        $this->prohibitTheTheMainEventResultsInAnException();

        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($form));

        $this->action->execute();
    }

    protected function prohibitTheTheMainEventResultsInAnException()
    {
        $result = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($result));
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function acceptsOnlyFormsByPreEvent()
    {
        $this->prohibitTheTheMainEventResultsInAnException();

        $form = $this->getMock('Zend\Form\FormInterface');
        $this->preEventReturns(
            $this->getEventResponseCollectionWithAValidResult($form),
            array('DkplusCrud\Util\EventResultVerifier', 'isForm')
        );

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     * @expectedExceptionMessage preCreate should result in a form
     */
    public function throwsAnExceptionWhenStrictModeNothingHasBeenFoundByPreEvent()
    {
        $this->preEventReturns($this->getEventResponseCollectionWithoutResults());

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersNotFoundEventWhenNotStrictAndNothingHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithoutResults());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->setStrict(false);
        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersNotFoundEventWhenNotStrictAndNoFormHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAnInvalidResult());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->setStrict(false);
        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsTheNotFoundEventResultWhenNotStrictAndNoEntityHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAnInvalidResult());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->setStrict(false);
        $this->assertSame($viewModel, $this->action->execute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     * @expectedExceptionMessage notFoundCreate should result in a valid controller response
     */
    public function throwsAnExceptionWhenNotStrictAndTheNotFoundEventReturnsCrap()
    {
        $this->preEventReturns($this->getEventResponseCollectionWithAnInvalidResult());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAnInvalidResult());

        $this->action->setStrict(false);
        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersMainEventToGetTheOutput()
    {
        $form      = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($form));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsTheResultOfTheMainEventWhenAFormHasBeenGotten()
    {
        $form      = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($form));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function passesTheFormAsParameterToTheMainEvent()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($form));
        $this->mainEventReturns(
            $this->getEventResponseCollectionWithAValidResult($viewModel),
            array('form' => $form)
        );

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     * @expectedExceptionMessage create should result in a valid controller response
     */
    public function throwsAnExceptionWhenTheMainEventReturnsCrap()
    {
        $form = $this->getMockForAbstractClass('Zend\Form\FormInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($form));
        $this->mainEventReturns($this->getEventResponseCollectionWithAnInvalidResult());

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersPostEventWithTheMainAndPreEventResults()
    {
        $form      = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($form));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));
        $this->postEventIsTriggeredWith(array('form' => $form, 'result' => $viewModel));

        $this->action->execute();
    }
}

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
class SingleEntityActionTest extends ActionTestCase
{

    protected function setUp()
    {
        $this->actionName = 'read';
        $this->action     = new SingleEntityAction($this->actionName);
        parent::setUp();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersPreEventToGetTheEntity()
    {
        $this->prohibitTheTheMainEventResultsInAnException();

        $entity = $this->getMock('stdClass');
        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($entity));

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
    public function acceptsAnythingNotNullAsEntity()
    {
        $this->prohibitTheTheMainEventResultsInAnException();

        $entity = $this->getMock('stdClass');
        $this->preEventReturns(
            $this->getEventResponseCollectionWithAValidResult($entity),
            array('DkplusCrud\Util\EventResultVerifier', 'isNotNull')
        );

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersNotFoundEventWhenNothingHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithoutResults());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersNotFoundEventWhenNoEntityHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAnInvalidResult());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsTheNotFoundEventResultWhenNoEntityHasBeenFound()
    {
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAnInvalidResult());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     */
    public function throwsAnExceptionWhenTheNotFoundEventReturnsCrap()
    {
        $this->preEventReturns($this->getEventResponseCollectionWithAnInvalidResult());
        $this->notFoundEventReturns($this->getEventResponseCollectionWithAnInvalidResult());

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersMainEventToGetTheOutput()
    {
        $entity = $this->getMock('stdClass');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($entity));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsTheResultOfTheMainEventWhenAnEntityHasBeenFound()
    {
        $entity = $this->getMock('stdClass');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($entity));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function passesTheEntityAsParameterToTheMainEvent()
    {
        $entity = $this->getMock('stdClass');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($entity));
        $this->mainEventReturns(
            $this->getEventResponseCollectionWithAValidResult($viewModel),
            array('entity' => $entity)
        );

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     */
    public function throwsAnExceptionWhenTheMainEventReturnsCrap()
    {
        $entity = $this->getMock('stdClass');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($entity));
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
        $entity    = $this->getMock('stdClass');
        $viewModel = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($entity));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));
        $this->postEventIsTriggeredWith(array('entity' => $entity, 'result' => $viewModel));

        $this->action->execute();
    }
}

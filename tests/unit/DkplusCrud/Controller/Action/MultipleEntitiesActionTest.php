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
class MultipleEntitiesActionTest extends ActionTestCase
{

    protected function setUp()
    {
        $this->actionName = 'list';
        $this->action     = new MultipleEntitiesAction($this->actionName);
        parent::setUp();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersPreEventToGetTheEntities()
    {
        $this->prohibitTheTheMainEventResultsInAnException();

        $collection = $this->getMock('stdClass');
        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($collection));

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
    public function acceptsAnythingNotNullAsEntities()
    {
        $this->prohibitTheTheMainEventResultsInAnException();

        $collection = $this->getMock('stdClass');
        $this->preEventReturns(
            $this->getEventResponseCollectionWithAValidResult($collection),
            array('DkplusCrud\Util\EventResultVerifier', 'isNotNull')
        );

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     * @expectedExceptionMessage preList should result in anything not null
     */
    public function throwsAnExceptionWhenNothingHasBeenFound()
    {
        $this->preEventReturns($this->getEventResponseCollectionWithoutResults());

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function triggersMainEventToGetTheOutput()
    {
        $collection = $this->getMock('stdClass');
        $viewModel  = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($collection));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function returnsTheResultOfTheMainEventWhenEntitiesHasBeenFound()
    {
        $collection = $this->getMock('stdClass');
        $viewModel  = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($collection));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));

        $this->assertSame($viewModel, $this->action->execute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function passesTheEntitiesAsParameterToTheMainEvent()
    {
        $collection = $this->getMock('stdClass');
        $viewModel  = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($collection));
        $this->mainEventReturns(
            $this->getEventResponseCollectionWithAValidResult($viewModel),
            array('entities' => $collection)
        );

        $this->action->execute();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     * @expectedException RuntimeException
     * @expectedExceptionMessage list should result in a valid controller response
     */
    public function throwsAnExceptionWhenTheMainEventReturnsCrap()
    {
        $collection = $this->getMock('stdClass');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($collection));
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
        $collection = $this->getMock('stdClass');
        $viewModel  = $this->getMockForAbstractClass('Zend\View\Model\ModelInterface');

        $this->preEventReturns($this->getEventResponseCollectionWithAValidResult($collection));
        $this->mainEventReturns($this->getEventResponseCollectionWithAValidResult($viewModel));
        $this->postEventIsTriggeredWith(array('entities' => $collection, 'result' => $viewModel));

        $this->action->execute();
    }
}

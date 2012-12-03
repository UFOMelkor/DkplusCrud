<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service;

use DkplusUnitTest\TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Service
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Service\Service
 */
class ServiceTest extends TestCase
{
    /** @var \DkplusCrud\Mapper\MapperInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $mapper;

    /** @var \DkplusCrud\FormHandler\FormHandlerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $formHandler;

    /** @var Service */
    private $service;

    /** @var \Zend\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $eventManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mapper       = $this->getMockForAbstractClass('DkplusCrud\Mapper\MapperInterface');
        $this->formHandler  = $this->getMockForAbstractClass('DkplusCrud\FormHandler\FormHandlerInterface');
        $this->eventManager = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $this->service      = new Service($this->mapper, $this->formHandler);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     * @testdox is a crud service
     */
    public function isCrudService()
    {
        $this->assertInstanceOf('DkplusCrud\Service\ServiceInterface', $this->service);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function needsAnEventManager()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManagerAwareInterface', $this->service);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function letTheFormStrategyCreateTheEntity()
    {
        $data = array('foo' => 'bar', 'baz' => 'bar');

        $this->formHandler->expects($this->once())
                           ->method('createEntity')
                           ->with($data);

        $this->service->create($data);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function storesTheCreatedEntityIntoTheMapper()
    {
        $createdEntity = $this->getMock('stdClass');

        $this->formHandler->expects($this->any())
                           ->method('createEntity')
                           ->will($this->returnValue($createdEntity));

        $this->mapper->expects($this->once())
                     ->method('save')
                     ->with($createdEntity);

        $this->service->create(array());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function returnsTheCreatedEntity()
    {
        $createdEntity = $this->getMock('stdClass');

        $this->mapper->expects($this->any())
                     ->method('save')
                     ->will($this->returnValue($createdEntity));

        $this->assertSame($createdEntity, $this->service->create(array()));
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function getsTheEntityToUpdateFromTheMapper()
    {
        $this->mapper->expects($this->once())
                     ->method('find')
                     ->with(34);

        $this->service->update(array(), 34);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function letTheFormStrategyUpdateTheEntity()
    {
        $entity = $this->getMock('stdClass');
        $data   = array('foo' => 'bar', 'baz' => 'bar');

        $this->mapper->expects($this->any())
                     ->method('find')
                     ->will($this->returnValue($entity));

        $this->formHandler->expects($this->once())
                           ->method('updateEntity')
                           ->with($data, $entity);

        $this->service->update($data, 15);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function storesTheUpdatedEntityIntoTheMapper()
    {
        $entity = $this->getMock('stdClass');

        $this->formHandler->expects($this->any())
                           ->method('updateEntity')
                           ->will($this->returnValue($entity));

        $this->mapper->expects($this->once())
                     ->method('save')
                     ->with($entity);

        $this->service->update(array(), 15);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function returnsTheUpdatedEntity()
    {
        $updatedEntity = $this->getMock('stdClass');

        $this->mapper->expects($this->any())
                     ->method('save')
                     ->will($this->returnValue($updatedEntity));

        $this->assertSame($updatedEntity, $this->service->update(array(), 10));
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function getsTheCreationFormFromFormStrategy()
    {
        $form = $this->getMock('Zend\Form\FormInterface');

        $this->formHandler->expects($this->once())
                           ->method('getCreationForm')
                           ->will($this->returnValue($form));

        $this->assertSame($form, $this->service->getCreationForm());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function findsEntitiesViaMapper()
    {
        $entity = $this->getMock('stdClass');

        $this->mapper->expects($this->once())
                     ->method('find')
                     ->with(76)
                     ->will($this->returnValue($entity));

        $this->assertSame($entity, $this->service->get(76));
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     * @expectedException DkplusBase\Service\Exception\EntityNotFound
     */
    public function doesNotCatchExceptionsWhenFinding()
    {
        $exception = $this->getMockIgnoringConstructor('DkplusBase\Service\Exception\EntityNotFound');

        $this->mapper->expects($this->once())
                     ->method('find')
                     ->will($this->throwException($exception));

        $this->service->get(5);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function deletesEntitiesViaMapper()
    {
        $entity = $this->getMock('stdClass');
        $this->mapper->expects($this->once())
                     ->method('delete')
                     ->with($entity);

        $this->service->delete($entity);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function getsAllEntitiesViaMapper()
    {
        $entities = array($this->getMock('stdClass'));

        $this->mapper->expects($this->once())
                     ->method('findAll')
                     ->will($this->returnValue($entities));

        $this->assertSame($entities, $this->service->getAll());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function getsTheEntityForTheUpdateFormFromTheMapper()
    {
        $this->mapper->expects($this->once())
                     ->method('find')
                     ->with(46);

        $this->service->getUpdateForm(46);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function getsTheUpdateFormFromTheFormHandler()
    {
        $entity = $this->getMock('stdClass');
        $form   = $this->getMockForAbstractClass('Zend\Form\FormInterface');

        $this->mapper->expects($this->any())
                     ->method('find')
                     ->will($this->returnValue($entity));
        $this->formHandler->expects($this->once())
                           ->method('getUpdateForm')
                           ->with($entity)
                           ->will($this->returnValue($form));

        $this->assertSame($form, $this->service->getUpdateForm(46));
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     * @expectedException DkplusBase\Service\Exception\EntityNotFound
     */
    public function doesNotCatchExceptionsWhenGettingUpdateForm()
    {
        $exception = $this->getMockIgnoringConstructor('DkplusBase\Service\Exception\EntityNotFound');

        $this->mapper->expects($this->once())
                     ->method('find')
                     ->will($this->throwException($exception));

        $this->service->getUpdateForm(25);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function getsAPaginatorWithAnAdapterFromTheMapper()
    {
        $adapter = $this->getMock('Zend\Paginator\Adapter\AdapterInterface');

        $this->mapper->expects($this->once())
                     ->method('getPaginationAdapter')
                     ->will($this->returnValue($adapter));

        $paginator = $this->service->getPaginator(5, 10);
        $this->assertSame($adapter, $paginator->getAdapter());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function assignsCurrentPageNumberToThePaginator()
    {
        $adapter = $this->getMock('Zend\Paginator\Adapter\AdapterInterface');

        $this->mapper->expects($this->once())
                     ->method('getPaginationAdapter')
                     ->will($this->returnValue($adapter));

        $paginator = $this->service->getPaginator(35, 10);
        $this->assertEquals(35, $paginator->getCurrentPageNumber());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function assignsEntitiesCountPerPageToThePaginator()
    {
        $adapter = $this->getMock('Zend\Paginator\Adapter\AdapterInterface');

        $this->mapper->expects($this->once())
                     ->method('getPaginationAdapter')
                     ->will($this->returnValue($adapter));

        $this->service->setItemCountPerPage(50);
        $paginator = $this->service->getPaginator(1);
        $this->assertEquals(50, $paginator->getItemCountPerPage());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function doesAlwaysHaveAnEventManager()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManagerInterface', $this->service->getEventManager());
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function addsAnIdentifierToTheEventManager()
    {
        $this->eventManager->expects($this->once())
                           ->method('addIdentifiers')
                           ->with('DkplusCrud\Service\Service');

        $this->service->setEventManager($this->eventManager);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function putsTheAddedEventManagerIntoTheMapperWhenImplementingEventManagerAware()
    {
        $mapper = $this->getMockBuilder('DkplusCrud\Mapper\DoctrineMapper')
                       ->disableOriginalConstructor()
                       ->getMock();

        $mapper->expects($this->once())
               ->method('setEventManager')
               ->with($this->eventManager);

        $service = new Service($mapper, $this->formHandler);
        $service->setEventManager($this->eventManager);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function attachesFeaturesToExistingEventManager()
    {
        $this->service->setEventManager($this->eventManager);

        $feature = $this->getMockForAbstractClass('DkplusCrud\Service\Feature\FeatureInterface');
        $feature->expects($this->once())
                ->method('attachTo')
                ->with($this->eventManager);

        $this->service->addFeature($feature);
    }

    /**
     * @test
     * @group unit
     * @group unit/service
     */
    public function attachesFeaturesToLaterAddedEventManager()
    {
        $feature = $this->getMockForAbstractClass('DkplusCrud\Service\Feature\FeatureInterface');
        $feature->expects($this->at(1))
                ->method('attachTo')
                ->with($this->eventManager);
        $this->service->addFeature($feature);

        $this->service->setEventManager($this->eventManager);
    }
}

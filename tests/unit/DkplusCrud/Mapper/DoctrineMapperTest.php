<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Mapper
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Mapper;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Mapper
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class DoctrineMapperTest extends TestCase
{
    /** @var \Doctrine\ORM\EntityManager|\PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var DoctrineMapper */
    private $mapper;

    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->mapper        = new DoctrineMapper($this->entityManager, 'stdClass');
    }

    /** @test */
    public function isACrudMapper()
    {
        $this->assertInstanceOf('DkplusCrud\Mapper\MapperInterface', $this->mapper);
    }

    /** @test */
    public function needsAnEventManager()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManagerAwareInterface', $this->mapper);
    }

    /** @test */
    public function savesEntitiesByPuttingThemIntoTheEntityManager()
    {
        $entity = $this->getMock('stdClass');

        $this->entityManager->expects($this->at(0))
                            ->method('persist')
                            ->with($entity);
        $this->entityManager->expects($this->at(1))
                            ->method('flush');

        $this->mapper->save($entity);
    }

    /** @test */
    public function returnsTheSavedEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->assertSame($entity, $this->mapper->save($entity));
    }

    /** @test */
    public function findsAnEntityUsingTheEntityManager()
    {
        $entity = $this->getMock('stdClass');

        $this->entityManager->expects($this->any())
                            ->method('find')
                            ->with('stdClass', 56)
                            ->will($this->returnValue($entity));

        $this->assertSame($entity, $this->mapper->find(56));
    }

    /**
     * @test
     * @expectedException DkplusBase\Service\Exception\EntityNotFound
     */
    public function throwsAnExceptionWhenNoEntityHasBeenFound()
    {
        $this->entityManager->expects($this->any())
                            ->method('find')
                            ->with('stdClass', 71)
                            ->will($this->returnValue(null));

        $this->mapper->find(71);
    }

    /** @test */
    public function canDeleteEntities()
    {
        $entity = $this->getMock('stdClass');

        $this->entityManager->expects($this->once())
                            ->method('remove')
                            ->with($entity);
        $this->entityManager->expects($this->once())
                            ->method('flush');

        $this->mapper->delete($entity);
    }

    /** @test */
    public function createsAQueryBuilderToFindEntities()
    {
        $query = $this->getMock('stdClass', array('execute'));

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
                             ->disableOriginalConstructor()
                             ->getMock();
        $queryBuilder->expects($this->once())
                     ->method('select')
                     ->with('e');
        $queryBuilder->expects($this->once())
                     ->method('from')
                     ->with('stdClass', 'e');
        $queryBuilder->expects($this->any())
                     ->method('getQuery')
                     ->will($this->returnValue($query));

        $this->entityManager->expects($this->any())
                            ->method('createQueryBuilder')
                            ->will($this->returnValue($queryBuilder));

        $this->mapper->findAll();
    }

    /** @test */
    public function returnsTheExecutedQueryAsFoundResult()
    {
        $executionResult = array('firstEntity', 'secondEntity');

        $query = $this->getMock('stdClass', array('execute'));

        $query->expects($this->once())
              ->method('execute')
              ->will($this->returnValue($executionResult));

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
                             ->disableOriginalConstructor()
                             ->getMock();

        $queryBuilder->expects($this->any())
                     ->method('getQuery')
                     ->will($this->returnValue($query));

        $this->entityManager->expects($this->any())
                            ->method('createQueryBuilder')
                            ->will($this->returnValue($queryBuilder));

        $this->assertSame($executionResult, $this->mapper->findAll());
    }

    /** @test */
    public function triggersAnEventWhenBuildingAQuery()
    {
        $query = $this->getMock('stdClass', array('execute'));

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
                             ->disableOriginalConstructor()
                             ->getMock();
        $queryBuilder->expects($this->any())
                     ->method('getQuery')
                     ->will($this->returnValue($query));

        $this->entityManager->expects($this->any())
                            ->method('createQueryBuilder')
                            ->will($this->returnValue($queryBuilder));

        $eventManager = $this->getMockForAbstractClass('Zend\EventManager\EventManagerInterface');
        $eventManager->expects($this->once())
                     ->method('trigger')
                     ->with('queryBuilder', $this->mapper, array('queryBuilder' => $queryBuilder));

        $this->mapper->setEventManager($eventManager);
        $this->mapper->findAll();
    }
}

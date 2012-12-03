<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Mapper
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Mapper;

use DkplusBase\Service\Exception\EntityNotFound as EntityNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginationAdapter;
use ZfcBase\EventManager\EventProvider;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Mapper
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class DoctrineMapper extends EventProvider implements MapperInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var string */
    protected $modelClass;

    public function __construct(EntityManager $entityManager, $modelClass)
    {
        $this->entityManager  = $entityManager;
        $this->modelClass     = $modelClass;
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function find($identifier)
    {
        $result = $this->entityManager->find($this->modelClass, $identifier);

        if ($result === null) {
            throw new EntityNotFoundException($identifier);
        }

        return $result;
    }

    public function findAll()
    {
        return $this->getQuery()->execute();
    }

    /** @return \Doctrine\ORM\Query */
    protected function getQuery()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('e');
        $queryBuilder->from($this->modelClass, 'e');

        $this->getEventManager()->trigger('queryBuilder', $this, array('queryBuilder' => $queryBuilder));

        return $queryBuilder->getQuery();
    }

    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @return \Zend\Paginator\Adapter\AdapterInterface
     * @codeCoverageIgnore
     */
    public function getPaginationAdapter()
    {
        $query = $this->getQuery();
        return new PaginationAdapter(new DoctrinePaginator($query));
    }
}

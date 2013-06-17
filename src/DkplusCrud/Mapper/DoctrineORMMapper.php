<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Mapper;

use DkplusBase\Service\Exception\EntityNotFound as EntityNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginationAdapter;
use ZfcBase\EventManager\EventProvider;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class DoctrineORMMapper extends EventProvider implements MapperInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var string */
    protected $modelClass;

    /** @var QueryBuilder[] */
    protected $queryBuilders = array();

    public function __construct(EntityManager $entityManager, $modelClass)
    {
        $this->entityManager  = $entityManager;
        $this->modelClass     = $modelClass;
    }

    public function persist($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
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

    public function findNamedCollection($name, array $order = array(), array $params = array(), $limit = null, $offset = null)
    {
        $queryBuilder = $this->getQueryBuilderByName($name);
        $this->applyData($queryBuilder, $params, $order, $limit, $offset);
        return $queryBuilder->execute();
    }

    protected function applyData(QueryBuilder $queryBuilder, array $params, array $order, $limit = null, $offset = null)
    {
        $queryBuilder->setParameters($params);

        foreach ($order as $property => $direction) {
            $queryBuilder->orderBy($this->normalizeProperty($property, $queryBuilder), $direction);
        }

        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }

        if ($offset !== null) {
            $queryBuilder->setFirstResult($offset);
        }
    }

    /**
     * @param string $property
     * @param QueryBuilder $queryBuilder
     * @return string
     */
    protected function normalizeProperty($property, QueryBuilder $queryBuilder)
    {
        $rootEntities = $queryBuilder->getRootEntities();
        $rootAliasas  = $queryBuilder->getRootAliases();

        foreach ($rootEntities as $i => $rootEntity) {
            $metadata = $this->entityManager->getClassMetadata($rootEntity);
            if (\in_array($property, $metadata->getColumnNames())) {
                return $rootAliasas[$i] . '.' . $property;
            }
        }

        foreach ($queryBuilder->getDQLPart('join') as $join) {
            foreach ($join as $rootEntity => $singleJoin) {
                $singleJoin = \array_pop($singleJoin); /* @var $singleJoin \Doctrine\ORM\Query\Expr\Join */

                $joinProperty = \substr($singleJoin->getJoin(), stripos($singleJoin->getJoin(), '.'));
                $targetClass  = $this->entityManager->getClassMetadata($rootEntity)
                                                    ->getAssociationTargetClass($joinProperty);

                $metadata = $this->entityManager->getClassMetadata($targetClass);
                if (\in_array($property, $metadata->getColumnNames())) {
                    return $singleJoin->getAlias() . '.' . $property;
                }
            }
        }

        throw new Exception;
    }

    public function getNamedPaginationAdapter($name, array $order = array(), array $params = array())
    {
        $queryBuilder = $this->getQueryBuilderByName($name);
        $this->applyData($queryBuilder, $params, $order);

        return new PaginationAdapter(new DoctrinePaginator($queryBuilder->getQuery()));
    }

    public function setQueryBuilder($name, QueryBuilder $queryBuilder)
    {
        $this->queryBuilders[$name] = $queryBuilder;
    }

    /**
     * @param string $name
     * @return QueryBuilder
     */
    protected function getQueryBuilderByName($name)
    {
        if (isset($this->queryBuilders[$name])) {
            return clone $this->queryBuilders[$name];
        }

        if ($name == self::DEFAULT_NAME) {
            return $this->createQueryBuilder();
        }

        throw new RuntimeException(sprintf('There is no querybuilder named "%s"', $name));
    }

    /** @return QueryBuilder */
    public function createQueryBuilder($alias = 'e')
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select($alias);
        $queryBuilder->from($this->modelClass, $alias);

        return $queryBuilder;
    }
}

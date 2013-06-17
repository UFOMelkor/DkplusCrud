<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Mapper;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
interface MapperInterface
{
    const DEFAULT_NAME = 'default';

    public function persist($entity);

    public function delete($entity);

    /**
     * @param mixed $identifier
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function find($identifier);

    /**
     * @param string $name
     * @param array $params
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     */
    public function findNamedCollection($name, array $order = array(), array $params = array(), $limit = null, $offset = null);

    /**
     * @param string $name
     * @param array $params
     * @param array $order
     * @return \Zend\Paginator\Adapter\AdapterInterface
     */
    public function getNamedPaginationAdapter($name, array $order = array(), array $params = array());
}

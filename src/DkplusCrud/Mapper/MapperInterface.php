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
    public function persist($entity);

    public function delete($entity);

    /**
     * @param mixed $identifier
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function find($identifier);

    /**
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     */
    public function findAll(array $order = array(), $limit = null, $offset = null);

    /**
     * @param string $name
     * @param array $params
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     */
    public function findByName($name, array $params = array(), array $order = array(), $limit = null, $offset = null);

    /**
     * @param array $order
     * @return \Zend\Paginator\Adapter\AdapterInterface
     */
    public function getPaginationAdapter(array $order = array());

    /**
     * @param string $name
     * @param array $params
     * @param array $order
     * @return \Zend\Paginator\Adapter\AdapterInterface
     */
    public function getPaginationAdapterByName($name, array $params = array(), array $order = array());
}

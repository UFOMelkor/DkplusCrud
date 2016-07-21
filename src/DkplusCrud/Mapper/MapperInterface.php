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
    public function save($entity);

    /**
     * @param mixed $identifier
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function find($identifier);

    public function findAll();

    public function delete($entity);

    /** @return \Zend\Paginator\Adapter\AdapterInterface */
    public function getPaginationAdapter();
}

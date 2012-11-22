<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Mapper
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Mapper;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Mapper
 * @author     Oskar Bley <oskar@programming-php.net>
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

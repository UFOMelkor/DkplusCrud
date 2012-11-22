<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Crud
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Crud
 * @author     Oskar Bley <oskar@programming-php.net>
 */
interface ServiceInterface
{
    /**
     * @param mixed $data
     * @return mixed created item
     */
    public function create($data);

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function get($identifier);

    public function getCreationForm();

    public function getAll();

    /**
     * @param mixed $data
     * @param mixed $identifier
     * @return mixed updated item
     */
    public function update($data, $identifier);

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function getUpdateForm($identifier);

    public function delete($entity);

    /**
     * @param int $pageNumber
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginator($pageNumber);

    /** @param int $value */
    public function setItemCountPerPage($value);
}

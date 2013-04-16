<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
interface ServiceInterface
{
    /**
     * @param mixed $data
     * @return mixed created item
     */
    public function create($data);

    /**
     * @param mixed $data
     * @param mixed $identifier
     * @return mixed updated item
     */
    public function update($data, $identifier);

    public function delete($object);

    public function getCreationForm();

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function getUpdateForm($identifier);

    /**
     * @param int $pageNumber
     * @param \Zend\Paginator\Paginator $itemCountPerPage
     */
    public function getPaginator($pageNumber, $itemCountPerPage = null, array $order = array());

    public function getPaginatorByName(
        $name,
        $pageNumber,
        $itemCountPerPage = null,
        array $params = array(),
        array $order = array()
    );

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function find($identifier);

    public function findAll(array $order = array());

    public function findByName($name, array $params = array(), array $order = array(), $limit = null, $offset = null);
}

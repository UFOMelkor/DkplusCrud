<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage FormHandler
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\FormHandler;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage FormHandler
 * @author     Oskar Bley <oskar@programming-php.net>
 */
interface FormHandlerInterface
{
    /**
     * @param mixed $data
     * @return mixed the created entity
     */
    public function createEntity($data);

    /**
     * @param mixed $data
     * @param mixed $entity
     * @return mixed the updated entity
     */
    public function updateEntity($data, $entity);

    /** @return \Zend\Form\FormInterface */
    public function getCreationForm();

    /**
     * @param mixed $entity The model
     * @return \Zend\Form\FormInterface
     */
    public function getUpdateForm($entity);
}

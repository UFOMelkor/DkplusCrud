<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\FormHandler;

use DkplusBase\Stdlib\Hydrator\HydrationFactoryInterface as HydrationFactory;
use Zend\Form\FormInterface as Form;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class FactoryFormHandler implements FormHandlerInterface
{
    /** @var Form */
    private $form;

    /** @var HydrationFactory */
    private $factory;

    public function __construct(Form $form, HydrationFactory $factory)
    {
        $this->form    = $form;
        $this->factory = $factory;
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function createEntity($data)
    {
        return $this->factory->create($data);
    }

    /**
     * @param mixed $data
     * @param mixed $entity
     * @return mixed
     */
    public function updateEntity($data, $entity)
    {
        $this->factory->hydrate($data, $entity);
        return $entity;
    }

    /** @return Form */
    public function getCreationForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $entity
     * @return Form
     */
    public function getUpdateForm($entity)
    {
        $data = $this->factory->extract($entity);
        $this->form->populateValues($data);
        return $this->form;
    }
}

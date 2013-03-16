<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\FormHandler;

use Zend\Form\FormInterface as Form;

/**
 * The ‘zf2-way’ of form handling.
 *
 * The form will do the most staff and you need to inject the hydrator into the form.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class BindFormHandler implements FormHandlerInterface
{
    /** @var Form */
    private $form;

    /** @var string */
    private $modelClass;

    /**
     * @param Form $form
     * @param string $modelClass The FQCN of the entity.
     */
    public function __construct(Form $form, $modelClass)
    {
        $this->form       = $form;
        $this->modelClass = $modelClass;
    }

    /**
     * @param object $data The entity binded from the form.
     * @return object The entity
     */
    public function createEntity($data)
    {
        return $data;
    }

    /**
     * @param object $data The entity binded from the form.
     * @param object $entity The entity binded from the form.
     * @return object The entity
     */
    public function updateEntity($data, $entity)
    {
        return $entity;
    }

    /** @return Form */
    public function getCreationForm()
    {
        $modelClass = $this->modelClass;
        $this->form->bind(new $modelClass);
        return $this->form;
    }

    /**
     * @param mixed $item The entity to bind to the form.
     * @return Form
     */
    public function getUpdateForm($item)
    {
        $this->form->bind($item);
        return $this->form;
    }
}

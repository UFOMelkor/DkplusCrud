<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class UpdateFormAction extends CreateFormAction
{
    /**
     *
     * If the action is strict, it throws an exception when no form has been get.
     *
     * Otherwise a notFound-Event will be thrown.
     *
     * @var boolean
     */
    protected $strict = false;
}

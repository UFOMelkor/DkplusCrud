<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Util
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Util;

use Zend\Form\FormInterface as Form;
use Zend\View\Model\ModelInterface as ViewModel;
use Zend\Stdlib\ResponseInterface as Response;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Util
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class EventResultVerifier
{
    public static function isForm($eventResult)
    {
        return $eventResult instanceof Form;
    }

    public static function isControllerResponse($eventResult)
    {
        return $eventResult instanceof ViewModel
            || $eventResult instanceof Response;
    }

    public static function isNotNull($eventResult)
    {
        return $eventResult !== null;
    }
}

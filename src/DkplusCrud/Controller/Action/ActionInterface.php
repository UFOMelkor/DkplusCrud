<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

use DkplusCrud\Controller\Controller;
use DkplusCrud\Controller\Feature\FeatureInterface as Feature;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
interface ActionInterface
{
    public function getName();

    public function addFeature(Feature $feature);

    public function setController(Controller $controller);

    public function attachTo(EventManager $events);
}

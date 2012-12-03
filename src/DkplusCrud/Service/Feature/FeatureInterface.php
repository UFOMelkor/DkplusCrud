<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use Zend\EventManager\EventInterface as Event;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
interface FeatureInterface
{
    public function attachTo(EventManager $events);

    public function execute(Event $event);
}

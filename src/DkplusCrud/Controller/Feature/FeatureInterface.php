<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
interface FeatureInterface
{
    public function attachTo($eventName, EventManager $events);

    public function input(Event\HttpInputEvent $event);
    public function model(Event\ModelEvent $event);
    public function output(Event\OutputEvent $event);
}

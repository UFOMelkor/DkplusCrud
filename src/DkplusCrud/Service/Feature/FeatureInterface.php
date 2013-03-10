<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature;

use Zend\EventManager\EventInterface as Event;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
interface FeatureInterface
{
    public function attachTo(EventManager $events);

    public function execute(Event $event);
}

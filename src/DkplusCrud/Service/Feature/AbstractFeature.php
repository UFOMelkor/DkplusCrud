<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature;

use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
abstract class AbstractFeature implements FeatureInterface
{
    /** @var string */
    protected $eventName;

    /** @var int */
    protected $priority = 1;

    /** @param int $priority */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /** @param EventManager $events */
    public function attachTo(EventManager $events)
    {
        $events->attach($this->eventName, array($this, 'execute'), $this->priority);
    }
}

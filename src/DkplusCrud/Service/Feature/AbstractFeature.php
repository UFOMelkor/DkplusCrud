<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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

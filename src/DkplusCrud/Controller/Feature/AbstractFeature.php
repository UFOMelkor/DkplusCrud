<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use DkplusCrud\Controller\Event;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
abstract class AbstractFeature implements FeatureInterface
{
    /** @var string */
    const EVENT_TYPE_INPUT = 'input';

    /** @var string */
    const EVENT_TYPE_MODEL = 'model';

    /** @var string */
    const EVENT_TYPE_OUTPUT = 'output';

    /** @var string[] */
    protected $eventTypes;

    /** @var int */
    protected $priority = 1;

    /** @var Controller */
    protected $controller;

    /** @param int $priority */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /** @param string|string[] $eventTypes */
    public function setEventTypes($eventTypes)
    {
        $this->eventTypes = (array) $eventTypes;
    }

    /**
     * @param string $eventName
     * @param EventManager $events
     */
    public function attachTo($eventName, EventManager $events)
    {
        foreach ((array) $this->eventTypes as $eventType) {
            $events->attach(
                $eventName . '.' . $eventType,
                array($this, $eventType),
                $this->priority
            );
        }
    }

    public function input(Event\HttpInputEvent $event)
    {
    }

    public function model(Event\ModelEvent $event)
    {
    }

    public function output(Event\OutputEvent $event)
    {
    }
}

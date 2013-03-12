<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
abstract class AbstractFeature implements FeatureInterface
{
    /** @var string */
    const EVENT_TYPE_MAIN = 'main';

    /** @var string */
    const EVENT_TYPE_PRE = 'pre';

    /** @var string */
    const EVENT_TYPE_POST = 'post';

    /** @var string */
    const EVENT_TYPE_NOT_FOUND = 'notFound';

    /** @var string */
    protected $eventType = self::EVENT_TYPE_MAIN;

    /** @var int */
    protected $priority = 1;

    /** @var Controller */
    protected $controller;

    /** @param int $priority */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /** @param string $eventType */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * @param string $eventName
     * @param EventManager $events
     */
    public function attachTo($eventName, EventManager $events)
    {
        $events->attach(
            $this->eventType == self::EVENT_TYPE_MAIN
            ? $eventName
            : $this->eventType . ucFirst($eventName),
            array($this, 'execute'),
            $this->priority
        );
    }
}

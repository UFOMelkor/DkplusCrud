<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /** @return Controller */
    public function getController()
    {
        return $this->controller;
    }
}

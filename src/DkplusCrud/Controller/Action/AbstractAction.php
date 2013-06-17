<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

use DkplusCrud\Controller\Feature\FeatureInterface as Feature;
use DkplusCrud\Controller\Controller;
use DkplusCrud\Controller\Event;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
abstract class AbstractAction implements ActionInterface
{
    /**
     * Correlates to the of the name of the method in ActionControllers.
     * E.g. update, read, …
     *
     * @var string
     */
    protected $name;

    /** @var Controller */
    protected $controller;

    /** @var Feature[] */
    protected $features = array();

    /** @var EventManager */
    protected $events;

    /** @var Event\HttpInputEvent */
    protected $inputEvent;

    /** @var Event\ModelEvent */
    protected $modelEvent;

    /** @var Event\OutputEvent */
    protected $outputEvent;

    /**
     * @param string $name Correlates to the name of the method in ActionControllers.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;
    }

    public function attachTo(EventManager $events)
    {
        $this->events = $events;
        foreach ($this->features as $feature) {
            $feature->attachTo($this->getName(), $events);
        }
    }

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param string $prefix Typically an emtpy string, pre, post or notFound
     */
    protected function triggerEvent(Event\AbstractEvent $event)
    {
        $this->events->trigger($this->getName() . '.' . $event->getType(), $event);
    }

    /**
     * @return Event\HttpInputEvent
     * @throws Exception\RuntimeException if event and controller have not been set.
     */
    public function getInputEvent()
    {
        if (!$this->inputEvent) {

            if (!$this->controller) {
                throw new Exception\RuntimeException(
                    'Could not provide a default input event because no controller has been injected'
                );
            }

            $this->inputEvent = new Event\HttpInputEvent($this->controller);
        }

        return $this->inputEvent;
    }

    public function setInputEvent(Event\HttpInputEvent $event)
    {
        $this->inputEvent = $event;
    }

    /**
     * @return Event\ModelEvent
     * @throws Exception\RuntimeException if no input event can be created.
     */
    public function getModelEvent()
    {
        if (!$this->modelEvent) {
            $this->modelEvent = new Event\ModelEvent($this->getInputEvent());
        }

        return $this->modelEvent;
    }

    public function setModelEvent(Event\ModelEvent $event)
    {
        $this->outputEvent = $event;
    }

    /**
     * @return Event\OutputEvent
     * @throws Exception\RuntimeException if no model event can be created.
     */
    public function getOutputEvent()
    {
        if (!$this->outputEvent) {
            $this->outputEvent = new Event\OutputEvent($this->getModelEvent());
        }

        return $this->outputEvent;
    }

    public function setOutputEvent(Event\OutputEvent $event)
    {
        $this->outputEvent = $event;
    }
}

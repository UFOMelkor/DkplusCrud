<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

use DkplusCrud\Controller\Feature\FeatureInterface as Feature;
use DkplusCrud\Controller\Controller;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
abstract class AbstractAction implements ActionInterface
{
    /** @var string */
    protected $name;

    /** @var Controller */
    protected $controller;

    /** @var Feature[] */
    protected $features = array();

    /** @var EventManager */
    protected $events;

    /** @param string $name */
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
            $feature->setController($this->controller);
            $feature->attachTo($this->getName(), $events);
        }
    }

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param string $prefix Typically an emtpy string, pre, post or notFound
     * @param array $arguments
     * @param callback $callback
     * @return mixed The last result of the event
     */
    protected function triggerEvent($prefix, $arguments = array(), $callback = null)
    {
        $eventName = $prefix == ''
                   ? $this->getName()
                   : $prefix . \ucFirst($this->getName());

        $result = $this->events->trigger($eventName, $this, $arguments, $callback);

        return count($result) > 0 && $result->stopped()
               ? $result->last()
               : null;
    }
}

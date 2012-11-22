<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use Zend\EventManager\EventInterface as Event;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
abstract class AbstractFeature implements FeatureInterface
{
    /** @var int */
    protected $priority = 1;

    /** @var Controller */
    protected $controller;

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function attachTo($eventName, EventManager $events)
    {
        $events->attach($eventName, array($this, 'execute'), $this->priority);
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

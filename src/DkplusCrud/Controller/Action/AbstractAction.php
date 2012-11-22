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
        foreach ($this->features as $feature) {
            $feature->setController($this->controller);
            $feature->attachTo($this->getName(), $events);
        }
    }

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }
}

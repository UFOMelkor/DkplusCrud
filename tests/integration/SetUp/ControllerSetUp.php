<?php
/**
 * @category   DkplusIntegration
 * @package    Crud
 * @subpackage SetUp
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Integration\SetUp;

use DkplusCrud\Controller\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\SimpleRouteStack;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\Mvc\Router\RouteInterface;

/**
 * @category   DkplusIntegration
 * @package    Crud
 * @subpackage SetUp
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class ControllerSetUp
{
    /** @var MvcEvent */
    protected $event;

    /** @var Request */
    protected $request;

    /** @var RouteMatch */
    protected $routeMatch;

    /** @var RouteStackInterface */
    protected $routeStack;

    /** @return MvcEvent */
    public function getEvent()
    {
        if ($this->event === null) {
            $this->event = new MvcEvent();
        }
        return $this->event;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    /** @return Request */
    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = new Request();
        }
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    /** @return RouteMatch */
    public function getRouteMatch()
    {
        if ($this->routeMatch === null) {
            $this->routeMatch = new RouteMatch(array());
        }
        return $this->routeMatch;
    }

    public function setRouteMatch($routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    /** @return RouteStackInterface */
    public function getRouteStack()
    {
        if ($this->routeStack === null) {
            $this->routeStack = new SimpleRouteStack();
        }
        return $this->routeStack;
    }

    public function setRouteStack(RouteStackInterface $routeStack)
    {
        $this->routeStack = $routeStack;
    }

    public function addRoute($name, RouteInterface $route)
    {
        $this->getRouteStack()->addRoute($name, $route);
    }

    public function setUp(Controller $controller)
    {
        $this->getEvent()->setRouter($this->getRouteStack());
        $this->getEvent()->setRouteMatch($this->getRouteMatch());
        $this->getEvent()->setRequest($this->getRequest());

        $controller->setEvent($this->getEvent());
        $controller->setServiceLocator(Bootstrap::getServiceManager());
        $controller->setPluginManager(Bootstrap::getControllerPluginManager());
    }
}

<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller;

use OutOfBoundsException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class Controller extends AbstractActionController
{
    /** @var Action\ActionInterface[] */
    protected $actions = array();


    public function addAction(Action\ActionInterface $action)
    {
        $action->setController($this);
        $this->actions[$action->getName()] = $action;
    }

    /**
     * @param string $actionName
     * @param Feature\FeatureInterface $feature
     * @throws OutOfBoundsException on non existing action
     */
    public function addFeature($actionName, Feature\FeatureInterface $feature)
    {
        if (empty($this->actions[$actionName])) {
            throw new OutOfBoundsException($actionName);
        }

        $this->actions[$actionName]->addFeature($feature);
    }

    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();

        foreach ($this->actions as $action) {
            $action->attachTo($this->getEventManager());
        }
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $actionResponse = $this->$method();

        $e->setResult($actionResponse);

        return $actionResponse;
    }
}

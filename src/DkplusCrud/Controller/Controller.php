<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller;

use OutOfBoundsException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
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

    public function onDispatch(MvcEvent $event)
    {
        $action = $event->getRouteMatch()->getParam('action');

        $result = empty($this->actions[$action])
                ? parent::onDispatch($event)
                : $this->actions[$action]->execute();

        $event->setResult($result);
        return $result;
    }
}

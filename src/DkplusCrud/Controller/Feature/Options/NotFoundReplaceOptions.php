<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature\Options;

use \Zend\Stdlib\AbstractOptions;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class NotFoundReplaceOptions extends AbstractOptions
{
    /** @var string */
    protected $crController = 'Application\Controller\Index';

    /** @var string */
    protected $crAction = 'index';

    /** @var string[] */
    protected $crRouteParams = array();

    /** @var string */
    protected $crRoute = null;

    /** @var string|null */
    protected $errorMessage;

    /** @var string */
    protected $messageNamespace = '404-not-found';

    /** @return string */
    public function getContentReplaceController()
    {
        return $this->crController;
    }

    /** @param string $controller */
    public function setContentReplaceController($controller)
    {
        $this->crController = $controller;
    }

    /** @return string */
    public function getContentReplaceAction()
    {
        return $this->crAction;
    }

    /** @param string $action */
    public function setContentReplaceAction($action)
    {
        $this->crAction = $action;
    }

    /** @return string[] */
    public function getContentReplaceRouteParams()
    {
        return $this->crRouteParams;
    }

    /** @param array $routeParams */
    public function setContentReplaceRouteParams(array $routeParams)
    {
        $this->crRouteParams = $routeParams;
    }

    public function getAllContentReplaceRouteParams()
    {
        
    }

    /** @return string */
    public function getContentReplaceRoute()
    {
        return $this->crRoute;
    }

    /** @param string $route */
    public function setContentReplaceRoute($route)
    {
        $this->crRoute = $route;
    }

    public function hasContentReplaceRoute()
    {

    }

    /** @return boolean */
    public function hasErrorMessage()
    {
        return \is_string($this->errorMessage);
    }

    /** @return string */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /** @param string|null $errorMessage */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /** @return string */
    public function getMessageNamespace()
    {
        return $this->messageNamespace;
    }

    /** @param string $messageNamespace */
    public function setMessageNamespace($messageNamespace)
    {
        $this->messageNamespace = $messageNamespace;
    }
}

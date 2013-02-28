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

    /** @var string[] */
    protected $crRouteParams = array('action' => 'index');

    /** @var string|nulls */
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

    /** @return string|null */
    public function getContentReplaceRoute()
    {
        return $this->crRoute;
    }

    /** @param string $route|nulls */
    public function setContentReplaceRoute($route)
    {
        $this->crRoute = $route;
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

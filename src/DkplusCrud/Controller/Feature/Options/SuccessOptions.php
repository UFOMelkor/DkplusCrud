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
class SuccessOptions extends AbstractOptions
{
    /** @var string|callable */
    protected $message = '';

    /** @var string */
    protected $messageNamespace = 'success';

    /** @var string|callable */
    protected $redirectRoute = 'home';

    /** @var array|callable */
    protected $redirectRouteParams = array();

    /** @return string */
    public function getRedirectRoute()
    {
        return $this->redirectRoute;
    }

    /** @param string $redirectRoute */
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = $redirectRoute;
    }

    /** @return array */
    public function getComputatedRedirectRouteParams($entity)
    {
        if (\is_callable($this->redirectRouteParams)) {
            return \call_user_func($this->redirectRouteParams, $entity);
        }

        return $this->redirectRouteParams;
    }

    /** @param array|callable $redirectRouteParams */
    public function setRedirectRouteParams($redirectRouteParams)
    {
        $this->redirectRouteParams = $redirectRouteParams;
    }

    /** @return string */
    public function getComputatedMessage($entity)
    {
        if (\is_callable($this->message)) {
            return \call_user_func($this->message, $entity);
        }

        return $this->message;
    }

    /** @param string|callable $message */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /** @return string */
    public function getMessageNamespace()
    {
        return $this->messageNamespace;
    }

    /** @param string $namespace */
    public function setMessageNamespace($namespace)
    {
        $this->messageNamespace = $namespace;
    }
}

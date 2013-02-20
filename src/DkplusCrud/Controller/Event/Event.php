<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Event
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Event;

use DkplusCrud\Controller\Controller;

use Zend\EventManager\Event as BaseEvent;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ModelInterface as ViewModel;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Event
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class Event extends BaseEvent
{
    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var ViewModel */
    protected $viewModel;
    
    /** @return Controller */
    public function getTarget()
    {
        return parent::getTarget();
    }

    /** @return Request */
    public function getRequest()
    {
        return $this->request;
    }

    /** @return Response */
    public function getResponse()
    {
        return $this->response;
    }

    /** @param \Zend\Http\Response $response */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /** @return ViewModel */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /** @param \Zend\View\Model\ModelInterface $viewModel */
    public function setViewModel(ViewModel $viewModel)
    {
        $this->viewModel = $viewModel;
    }
}

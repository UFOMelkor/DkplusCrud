<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller;

use DkplusCrud\Controller\Controller;

use Zend\EventManager\Event as BaseEvent;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ModelInterface as ViewModelInterface;
use Zend\View\Model\ViewModel;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class Event extends BaseEvent
{
    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var ViewModelInterface */
    protected $viewModel;

    /** @var mixed */
    protected $entity;

    /** @var array */
    protected $entities;

    protected $identifier;

    protected $form;

    public function __construct(Controller $controller)
    {
        parent::__construct(null, $controller);

        $this->setRequest($controller->getRequest());
        $this->setResponse($controller->getResponse());
    }

    /** @return Controller */
    public function getController()
    {
        return parent::getTarget();
    }

    /** @return Request */
    public function getRequest()
    {
        return $this->request;
    }

    private function setRequest(Request $request)
    {
        $this->request = $request;
        $this->setParam('request', $request);
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
        $this->setParam('response', $response);
    }

    /** @return ViewModelInterface */
    public function getViewModel()
    {
        if (!$this->viewModel) {
            $this->setViewModel(new ViewModel());
        }
        return $this->viewModel;
    }

    /** @param \Zend\View\Model\ModelInterface $viewModel */
    public function setViewModel(ViewModelInterface $viewModel)
    {
        $this->viewModel = $viewModel;
        $this->setParam('viewModel', $viewModel);
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        $this->form = $form;
    }
}

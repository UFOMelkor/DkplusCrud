<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller;

use DkplusCrud\Controller\Controller;
use Zend\EventManager\Event as BaseEvent;
use Zend\Form\FormInterface as Form;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ModelInterface as ViewModelInterface;
use Zend\View\Model\ViewModel as DefaultViewModel;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
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

    /** @var mixed */
    protected $entities;

    /** @var mixed */
    protected $identifier;

    /** @var Form */
    protected $form;

    /** @var ViewModelInterface|Response */
    protected $result;

    public function __construct(Controller $controller)
    {
        parent::__construct(null, $controller);
    }

    /** @return Controller */
    public function getController()
    {
        return parent::getTarget();
    }

    /** @return Request */
    public function getRequest()
    {
        $this->init();
        return $this->request;
    }

    private function init()
    {
        if (!$this->request) {
            $this->setRequest($this->getController()->getRequest());
        }

        if (!$this->response) {
            $this->response = $this->getController()->getResponse();
            $this->setParam('response', $this->response);
        }

        if (!$this->viewModel) {
            $this->viewModel = new DefaultViewModel();
            $this->setParam('viewModel', $this->viewModel);
        }

        if (!$this->result) {
            $this->setResult($this->viewModel);
        }
    }

    private function setRequest(Request $request)
    {
        $this->request = $request;
        $this->setParam('request', $request);
    }

    /** @return Response */
    public function getResponse()
    {
        $this->init();
        return $this->response;
    }

    /** @param Response $response */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        $this->setParam('response', $response);
        $this->setResult($response);
    }

    /** @return ViewModelInterface */
    public function getViewModel()
    {
        $this->init();
        return $this->viewModel;
    }

    public function setViewModel(ViewModelInterface $viewModel)
    {
        $this->viewModel = $viewModel;
        $this->setParam('viewModel', $viewModel);
        $this->setResult($viewModel);
    }

    /** @return boolean */
    public function hasEntity()
    {
        return (boolean) $this->entity;
    }

    /**
     * @return mixed
     * @throws ConfigurationError if no entity is available.
     */
    public function getEntity()
    {
        if ($this->entity === null) {
            throw new ConfigurationError($this->getName(), 'entity');
        }
        return $this->entity;
    }

    /** @param mixed $entity */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->setParam('entity', $entity);
    }

    /**
     * @return mixed
     * @throws ConfigurationError if no entities are available.
     */
    public function getEntities()
    {
        if (!$this->entities) {
            throw new ConfigurationError($this->getName(), 'entities');
        }
        return $this->entities;
    }

    /** @param mixed $entities */
    public function setEntities($entities)
    {
        $this->entities = $entities;
        $this->setParam('entities', $entities);
    }

    /** @return boolean */
    public function hasIdentifier()
    {
        return $this->identifier !== null;
    }

    /**
     * @return mixed
     * @throws ConfigurationError if no identifier is available.
     */
    public function getIdentifier()
    {
        if ($this->identifier === null) {
            throw new ConfigurationError($this->getName(), 'identifier');
        }
        return $this->identifier;
    }

    /** @param mixed $identifier */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        $this->setParam('identifier', $identifier);
    }

    /** @return boolean */
    public function hasForm()
    {
        return (boolean) $this->form;
    }

    /**
     * @return Form
     * @throws ConfigurationError if no form is available.
     */
    public function getForm()
    {
        if (!$this->form) {
            throw new ConfigurationError($this->getName(), 'form');
        }
        return $this->form;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
        $this->setParam('form', $form);
    }

    /** @return ViewModelInterface|Response The result that should be returned from the action */
    public function getResult()
    {
        $this->init();
        return $this->result;
    }

    private function setResult($result)
    {
        $this->result = $result;
        $this->setParam('__RESULT__', $result);
    }

    public function getParam($name, $default = null)
    {
        $this->init();
        return parent::getParam($name, $default);
    }

    public function getParams()
    {
        $this->init();
        return parent::getParams();
    }
}

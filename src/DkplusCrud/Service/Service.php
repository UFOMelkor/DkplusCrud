<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service;

use DkplusCrud\FormHandler\FormHandlerInterface;
use DkplusCrud\Mapper\MapperInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface as EventManagerInterface;
use Zend\EventManager\EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class Service implements ServiceInterface, EventManagerAwareInterface
{
    /** @var MapperInterface */
    protected $mapper;

    /** @var FormHandlerInterface */
    protected $formHandler;

    /** @var EventManager */
    protected $eventManager;

    /** @var int */
    protected $itemCountPerPage = 10;

    /** @var Feature\FeatureInterface[] */
    protected $features = array();

    public function __construct(MapperInterface $mapper, FormHandlerInterface $formHandler)
    {
        $this->mapper      = $mapper;
        $this->formHandler = $formHandler;
    }

    public function getCreationForm()
    {
        return $this->formHandler->getCreationForm();
    }

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function getUpdateForm($identifier)
    {
        $item = $this->mapper->find($identifier);
        return $this->formHandler->getUpdateForm($item);
    }


    public function create($data)
    {
        $item = $this->formHandler->createEntity($data);
        return $this->mapper->save($item);
    }

    public function update($data, $identifier)
    {
        $item = $this->formHandler->updateEntity($data, $this->mapper->find($identifier));
        return $this->mapper->save($item);
    }

    public function delete($entity)
    {
        $this->mapper->delete($entity);
    }

    /**
     * @throws \DkplusBase\Service\Exception\EntityNotFound
     */
    public function find($identifier)
    {
        return $this->mapper->find($identifier);
    }

    public function findAll(array $order = array())
    {
        return $this->mapper->findAll($order);
    }

    public function findByName($name, array $params = array(), array $order = array(), $limit = null, $offset = null)
    {
        return $this->mapper->findByName($name, $params, $order, $limit, $offset);
    }

    /**
     * @param int $pageNumber
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginator($pageNumber, $itemCountPerPage = null, array $order = array())
    {
        if ($itemCountPerPage === null) {
            $itemCountPerPage = $this->itemCountPerPage;
        }

        $adapter   = $this->mapper->getPaginationAdapter($order);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->itemCountPerPage);
        return $paginator;
    }

    public function getPaginatorByName(
        $name,
        $pageNumber,
        $itemCountPerPage = null,
        array $params = array(),
        array $order = array()
    ) {
        if ($itemCountPerPage === null) {
            $itemCountPerPage = $this->itemCountPerPage;
        }

        $adapter   = $this->mapper->getPaginationAdapterByName($name, $params, $order);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->itemCountPerPage);
        return $paginator;
    }

    /** @param int $itemCountPerPage */
    public function setItemCountPerPage($itemCountPerPage)
    {
        $this->itemCountPerPage = $itemCountPerPage;
    }

    /** @return \EventManagerInterface */
    public function getEventManager()
    {
        if ($this->eventManager === null) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }

    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers('DkplusCrud\Service\Service');
        $this->eventManager = $eventManager;

        if ($this->mapper instanceof EventManagerAwareInterface) {
            $this->mapper->setEventManager($this->eventManager);
        }

        foreach ($this->features as $feature) {
            $feature->attachTo($this->eventManager);
        }
    }

    public function addFeature(Feature\FeatureInterface $feature)
    {
        $feature->attachTo($this->getEventManager());
        $this->features[] = $feature;
    }
}

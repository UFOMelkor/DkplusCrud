<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\ServiceInterface as Service;
use Zend\EventManager\EventInterface;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class Deletion extends AbstractFeature
{
    /** @var Options\SuccessOptions */
    protected $options;

    /** @var Service */
    protected $service;

    public function __construct(Service $service, Options\SuccessOptions $options)
    {
        $this->service = $service;
        $this->options = $options;
    }

    public function execute(EventInterface $event)
    {
        $ctrl        = $this->getController();
        $entity      = $event->getParam('entity');
        $message     = $this->options->getComputatedMessage($entity);
        $route       = $this->options->getRedirectRoute();
        $routeParams = $this->options->getComputatedRedirectRouteParams($entity);

        $this->service->delete($entity);

        return $ctrl->dsl()->redirect()->to()->route($route, $routeParams)
                           ->with()->success()->message($message);

        $event->useResponseAsResult();
        $event->setResponse($event->getController()->redirect()->toRoute($route, $routeParams));
        $event->getController()->setNamespace('success')->addMessage($message);
    }
}

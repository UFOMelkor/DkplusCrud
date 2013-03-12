<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\ServiceInterface as Service;
use DkplusCrud\Controller\Event;

/**
 * Deletes an entity and then redirects and adds a flash message.
 *
 * Requires an entity.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
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

    public function execute(Event $event)
    {
        $entity      = $event->getEntity();
        $message     = $this->options->getComputatedMessage($entity);
        $messageNs   = $this->options->getMessageNamespace();
        $route       = $this->options->getRedirectRoute();
        $routeParams = $this->options->getComputatedRedirectRouteParams($entity);

        $this->service->delete($entity);

        $event->setResponse($event->getController()->redirect()->toRoute($route, $routeParams));
        $event->getController()->flashMessenger()->setNamespace($messageNs);
        $event->getController()->flashMessenger()->addMessage($message);
    }
}

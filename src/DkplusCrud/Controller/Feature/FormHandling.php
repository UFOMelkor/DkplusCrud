<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use DkplusCrud\Service\ServiceInterface as Service;
use Zend\Http\Response;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class FormHandling extends AbstractFeature
{
    /** @var Options\SuccessOptions */
    protected $options;

    /** @var Service */
    protected $service;

    /** @var string */
    protected $identifier = 'id';

    /** @var boolean */
    protected $handleAjaxRequest = false;

    /**
     * @param Service $storage
     * @param Options\SuccessOptions $options
     */
    public function __construct(Service $service, Options\SuccessOptions $options)
    {
        $this->service  = $service;
        $this->options  = $options;
    }

    public function handleAjaxRequest()
    {
        $this->handleAjaxRequest = true;
    }

    public function execute(Event $event)
    {
        if (!$event->getRequest()->isXmlHttpRequest()
            || $this->handleAjaxRequest
        ) {

            $controller = $event->getController();
            $prg        = $controller->postRedirectGet();

            if ($prg instanceof Response) {
                $event->setResponse($prg);
                $event->stopPropagation();
                return;
            }

            $form = $event->getForm();
            $event->getViewModel()->setVariable('form', $form);

            if (!\is_array($prg)) {
                return;
            }

            $form->setData($prg);

            if ($form->isValid()) {

                $entity = $event->hasIdentifier()
                        ? $this->service->update($form->getData(), $event->getIdentifier())
                        : $this->service->create($form->getData());

                $messageNamespace    = $this->options->getMessageNamespace();
                $message             = $this->options->getComputatedMessage($entity);
                $redirectRoute       = $this->options->getRedirectRoute();
                $redirectRouteParams = $this->options->getComputatedRedirectRouteParams($entity);

                $controller->flashMessenger()->setNamespace($messageNamespace);
                $controller->flashMessenger()->addMessage($message);

                $event->setResponse($controller->redirect()->toRoute($redirectRoute, $redirectRouteParams));
                $event->stopPropagation();
            }
        }
    }
}

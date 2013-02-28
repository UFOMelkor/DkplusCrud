<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
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

    /** @var callable */
    protected $storage;

    /** @var string */
    protected $identifier = 'id';

    /** @var boolean */
    protected $handleAjaxRequest = false;

    /**
     * @param callable $storage
     * @param Options\SuccessOptions $options
     */
    public function __construct($storage, Options\SuccessOptions $options)
    {
        $this->storage  = $storage;
        $this->options  = $options;
    }

    public function handleAjaxRequest()
    {
        $this->handleAjaxRequest = true;
    }

    public function execute(Event $event)
    {
        $form = $event->getForm();
        $event->getViewModel()->setVariable('form', $form);

        if (!$event->getRequest()->isXmlHttpRequest()
            || $this->handleAjaxRequest
        ) {

            $identifier = $form->get($this->identifier)->getValue();
            $controller = $event->getController();
            $prg        = $controller->postRedirectGet();

            if ($prg instanceof Response) {
                $event->setResponse($prg);
                $event->stopPropagation();
                return;
            }

            $form->setData($prg);

            if ($form->isValid()) {

                $entity = \call_user_func($this->storage, $form->getData(), $identifier);

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

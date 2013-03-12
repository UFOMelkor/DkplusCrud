<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use Zend\View\Model\JsonModel;

/**
 * Assigns the messages from the form to the view model.
 * For this the form must be validated, but there will be no handling of a valid
 * form (for this look at the [FormHandling](#formhandling)-Feature).
 * If there is no instance of Zend\View\Model\JsonModel available the view model will be overriden.
 * The feature can handle post and query data by determining the request-method.
 * As the name suggests it will only does its work if an ajax request has been detected.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class AjaxFormSupport extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    public function execute(Event $event)
    {
        if ($event->getRequest()->isXmlHttpRequest()) {

            $data = $event->getRequest()->isPost()
                  ? $event->getRequest()->getPost()->toArray()
                  : $event->getRequest()->getQuery()->toArray();

            $form = $event->getForm();
            $form->setData($data);
            $event->getForm()->isValid();

            if (!$event->getViewModel() instanceof JsonModel) {
                $event->setViewModel(new JsonModel());
            }

            $event->getViewModel()->setVariables($event->getForm()->getMessages());
        }
    }
}

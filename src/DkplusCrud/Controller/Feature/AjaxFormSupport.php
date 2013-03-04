<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use Zend\View\Model\JsonModel;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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

<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusControllerDsl\Dsl\DslInterface as Dsl;
use RuntimeException;
use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class AjaxLayoutDisabling extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    public function execute(Event $event)
    {
        $ctrl = $this->getController();
        $dsl  = $event->getParam('result');

        if (!$dsl instanceof Dsl) {
            throw new RuntimeException('missing dsl');
        }

        return $dsl->onAjaxRequest($ctrl->dsl()->disableLayout());

        if ($event->getRequest()->isXmlHttpRequest()) {
            $event->getViewModel()->setTermial(true);
        }
    }
}

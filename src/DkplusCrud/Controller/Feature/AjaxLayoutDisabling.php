<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class AjaxLayoutDisabling extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    public function execute(Event $event)
    {
        if ($event->getRequest()->isXmlHttpRequest()) {
            $event->getViewModel()->setTerminal(true);
        }
    }
}

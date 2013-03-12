<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use DkplusCrud\Service\ServiceInterface as Service;

/**
 * Gets a form from the service and puts it into the event for further use.
 *
 * Needs an identifer.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class UpdateFormProvider extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_PRE;

    /** @var Service */
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function execute(Event $event)
    {
        $event->setForm($this->service->getUpdateForm($event->getIdentifier()));
    }
}

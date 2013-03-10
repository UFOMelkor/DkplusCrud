<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use DkplusCrud\Service\ServiceInterface as Service;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class EntityProvider extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_PRE;

    /** @var Service */
    protected $service;

    /** @param Service $service */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function execute(Event $event)
    {
        $event->setEntity($this->service->get($event->getIdentifier()));
    }
}

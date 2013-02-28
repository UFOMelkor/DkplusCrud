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

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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

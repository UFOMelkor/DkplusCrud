<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\ServiceInterface as Service;
use DkplusCrud\Controller\Event;

/**
 * Deletes an entity.
 *
 * Requires an entity to be set.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class Deletion extends AbstractFeature
{
    /** @var Service */
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function execute(Event $event)
    {
        $this->service->delete($event->getEntity());
    }
}

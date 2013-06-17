<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event\ModelEvent;
use DkplusCrud\Service\ServiceInterface as Service;

/**
 * Gets entities as paginator from the service and puts them into the event for further use.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class PaginationProvider extends AbstractFeature
{
    /** @var string */
    protected $eventTypes = self::EVENT_TYPE_MODEL;

    /** @var Service */
    protected $service;

    /** @var string */
    protected $pageParameter;

    /**
     * @param Service $service
     * @param string $pageParameter The route parameter that contains the current page number.
     */
    public function __construct(Service $service, $pageParameter = 'page')
    {
        $this->service       = $service;
        $this->pageParameter = (string) $pageParameter;
    }

    public function model(ModelEvent $event)
    {
        $pageNumber = $event->getController()
                            ->getEvent()
                            ->getRouteMatch()
                            ->getParam($this->pageParameter);
        $event->setEntities($this->service->getPaginator($pageNumber));
    }
}

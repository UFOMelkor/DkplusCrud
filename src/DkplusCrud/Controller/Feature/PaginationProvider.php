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
class PaginationProvider extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_PRE;

    /** @var Service */
    protected $service;

    /** @var string */
    protected $pageParameter;

    /**
     * @param Service $service
     * @param string $pageParameter
     */
    public function __construct(Service $service, $pageParameter = 'page')
    {
        $this->service       = $service;
        $this->pageParameter = (string) $pageParameter;
    }

    public function execute(Event $event)
    {
        $pageNumber = $event->getController()
                            ->getEvent()
                            ->getRouteMatch()
                            ->getParam($this->pageParameter);
        $event->setEntities($this->service->getPaginator($pageNumber));
    }
}

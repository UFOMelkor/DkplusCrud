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
        return $this->service->getPaginator($pageNumber);
    }
}

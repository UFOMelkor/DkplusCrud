<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\ServiceInterface as Service;
use Zend\EventManager\EventInterface;

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

    /**
     * @param Service $service
     * @param string $routeMatchParam
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function execute(EventInterface $event)
    {
        $identifier = $event->getParam('identifier');
        return $this->service->get($identifier);
    }
}

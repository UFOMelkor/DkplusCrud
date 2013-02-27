<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class IdentifierProvider extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_PRE;

    /** @var int */
    protected $priority = 2;

    /** @var string */
    protected $routeMatchParam;

    /**
     * @param string $routeMatchParam
     */
    public function __construct($routeMatchParam = 'id')
    {
        $this->routeMatchParam = (string) $routeMatchParam;
    }

    public function execute(Event $event)
    {
        $event->setParam(
            'identifier',
            $event->getController()->getEvent()->getRouteMatch()->getParam($this->routeMatchParam)
        );
    }
}

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
        $event->setIdentifier(
            $event->getController()->getEvent()->getRouteMatch()->getParam($this->routeMatchParam)
        );
    }
}

<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * Gets an identifier from the route match and puts him into the event.
 *
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

    /** @param string $routeParam The name of the route param that contains the id */
    public function __construct($routeParam = 'id')
    {
        $this->routeMatchParam = (string) $routeParam;
    }

    public function execute(Event $event)
    {
        $event->setIdentifier(
            $event->getController()->getEvent()->getRouteMatch()->getParam($this->routeMatchParam)
        );
    }
}

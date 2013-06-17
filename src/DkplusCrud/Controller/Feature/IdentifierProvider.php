<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event\HttpInputEvent;

/**
 * Gets an identifier from the route match and puts him into the event.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class IdentifierProvider extends AbstractFeature
{
    /** @var string */
    protected $eventTypes = self::EVENT_TYPE_INPUT;

    /** @var int */
    protected $priority = 2;

    /** @var string */
    protected $routeMatchParam;

    /** @param string $routeParam The name of the route param that contains the id */
    public function __construct($routeParam = 'id')
    {
        $this->routeMatchParam = (string) $routeParam;
    }

    public function input(HttpInputEvent $event)
    {
        $event->setIdentifier(
            $event->getController()->getEvent()->getRouteMatch()->getParam($this->routeMatchParam)
        );
    }
}

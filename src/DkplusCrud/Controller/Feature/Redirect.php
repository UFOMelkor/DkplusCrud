<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * Redirects to a route.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.2.0
 */
class Redirect extends AbstractFeature
{
    /** @var string|null */
    protected $route;

    /** @var array|callable */
    protected $routeParams;

    /** @var boolean */
    protected $ignoreMissingEntity;

    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    /**
     * @param string $route
     * @param array|callable $routeParams May become a callable so you can make them depending from an entity.
     *                                    If an entity exists, the entity will be passed as first parameter.
     * @param boolean $ignoreMissingEntity By default a missing entity will be ignored and the redirect will be done
     *                                     anyway.
     *                                     By setting this to false there will be no redirect on a missing entity.
     */
    public function __construct($route, $routeParams = array(), $ignoreMissingEntity = true)
    {
        $this->route               = (string) $route;
        $this->routeParams         = $routeParams;
        $this->ignoreMissingEntity = (boolean) $ignoreMissingEntity;
    }

    public function execute(Event $event)
    {
        if (!$this->ignoreMissingEntity
            && !$event->hasEntity()
        ) {
            return;
        }

        $routeParams = $this->routeParams;

        if (\is_callable($this->routeParams)) {
            $routeParams = $event->hasEntity()
                         ? \call_user_func($routeParams, $event->getEntity())
                         : \call_user_func($routeParams);
        }

        $event->setResponse($event->getController()->redirect()->toRoute($this->route, $routeParams));
    }
}

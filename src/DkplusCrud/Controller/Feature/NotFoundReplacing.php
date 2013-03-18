<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * Could be used if an entity has not been found.
 *
 * It sets a 404 response code and returns the content of another action,
 * so you could show a list of related entities or something else.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class NotFoundReplacing extends AbstractFeature
{
    /** @var string The name of controller as he is used in the controller manager. */
    private $controllerName;

    /** @var string[] The route params like the action. */
    private $routeParams;

    /** @var string|null The name of the route to use or null if the current route should be used. */
    private $route;

    /** @var string */
    protected $eventType = self::EVENT_TYPE_NOT_FOUND;

    /**
     * @param string $controllerName The name of controller as he is used in the controller manager.
     * @param string[] $routeParams The route params like the controller-action.
     * @param string $route The name of the route to use or null if the current route should be used.
     */
    public function __construct($controllerName, array $routeParams = null, $route = null)
    {
        $this->controllerName = $controllerName;
        $this->routeParams    = $routeParams;
        $this->route          = $route;
    }

    public function execute(Event $event)
    {
        $event->setViewModel(
            $event->getController()
                  ->notFoundForward()
                  ->dispatch($this->controllerName, $this->routeParams, $this->route)
        );
    }
}

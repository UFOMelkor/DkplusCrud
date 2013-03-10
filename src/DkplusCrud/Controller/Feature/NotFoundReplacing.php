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
class NotFoundReplacing extends AbstractFeature
{
    /** @var Options\NotFoundReplaceOptions */
    private $options;

    public function __construct(Options\NotFoundReplaceOptions $options)
    {
        $this->options = $options;
    }

    public function execute(Event $event)
    {
        $controller = $this->options->getContentReplaceController();
        $params     = $this->options->getContentReplaceRouteParams();
        $route      = $this->options->getContentReplaceRoute();
        $event->setViewModel($event->getController()->notFoundForward()->dispatch($controller, $params, $route));

        if ($this->options->hasErrorMessage()) {
            $event->getController()->flashMessenger()->setNamespace($this->options->getMessageNamespace());
            $event->getController()->flashMessenger()->addMessage($this->options->getErrorMessage());
        }
    }
}

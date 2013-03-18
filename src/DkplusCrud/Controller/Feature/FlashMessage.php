<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * Adds a flash message.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.2.0
 */
class FlashMessage extends AbstractFeature
{
    /** @var string|callable */
    protected $message;

    /** @var string|null */
    protected $namespace;

    /** @var boolean */
    protected $ignoreMissingEntity;

    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    /**
     * @param string|callable $message May become a callable so you can make the message depending from an entity.
     *                                 If an entity exists, the entity will be passed as first parameter.
     * @param string|null $namespace Can be used to set a flash-message-namespace.
     * @param boolean $ignoreMissingEntity By default a missing entity will be ignored and the flash message will
     *                                     be added anyway.
     *                                     By setting this to false there will be no flash message on a missing entity.
     */
    public function __construct($message, $namespace = null, $ignoreMissingEntity = true)
    {
        $this->message             = $message;
        $this->namespace           = $namespace;
        $this->ignoreMissingEntity = $ignoreMissingEntity;
    }

    public function execute(Event $event)
    {
        if (!$this->ignoreMissingEntity
            && !$event->hasEntity()
        ) {
            return;
        }

        if ($this->namespace !== null) {
            $event->getController()->flashMessenger()->setNamespace($this->namespace);
        }

        $message = $this->message;

        if (\is_callable($message)) {
            $message = $event->hasEntity()
                     ? \call_user_func($message, $event->getEntity())
                     : \call_user_func($message);
        }

        $event->getController()->flashMessenger()->addMessage($message);
    }
}

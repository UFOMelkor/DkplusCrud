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
class Assigning extends AbstractFeature
{
    /** @var string */
    protected $assignAlias;

    /** @var string */
    protected $eventParameter;

    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    public function __construct($eventParameter, $assignAlias)
    {
        $this->eventParameter = $eventParameter;
        $this->assignAlias    = $assignAlias;
    }

    public function execute(Event $event)
    {
        $assignable = $event->getParam($this->eventParameter);
        $event->getViewModel()->setVariable($this->assignAlias, $assignable);
    }
}

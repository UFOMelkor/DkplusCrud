<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;

/**
 * Assigns a variable to the view model.
 *
 * By default the variable will be get from the event,
 * so you can assign a paginator or anything else stored in the event object.
 * If you want to assign a variable directly you have to call <code>useEvent(false)</code>.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class Assigning extends AbstractFeature
{
    /** @var string */
    protected $alias;

    /** @var string */
    protected $value;

    /** @var string */
    protected $eventType = self::EVENT_TYPE_POST;

    /** @var boolean */
    protected $useEvent = true;

    public function __construct($value, $alias)
    {
        $this->value = $value;
        $this->alias = $alias;
    }

    /**
     * Should the value be fetched from the event or should <code>$value</code> be used directly?
     *
     * Default is fetching from event.
     *
     * @param boolean $flag
     */
    public function useEvent($flag)
    {
        $this->useEvent = (boolean) $flag;
    }

    public function execute(Event $event)
    {
        $value = $this->useEvent
               ? $event->getParam($this->value)
               : $this->value;
        $event->getViewModel()->setVariable($this->alias, $value);
    }
}

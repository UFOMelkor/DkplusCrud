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

<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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
        $dsl        = $event->getParam('result');
        return $dsl->assign($assignable)->as($this->assignAlias);
    }
}

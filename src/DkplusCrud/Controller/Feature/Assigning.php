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
    protected $template;

    public function __construct($assignAlias, $eventParameter, $template)
    {
        $this->assignAlias    = $assignAlias;
        $this->eventParameter = $eventParameter;
        $this->template       = $template;
    }

    public function execute(Event $event)
    {
        $controller = $this->getController();
        $assignable = $event->getParam($this->eventParameter);
        return $controller->dsl()->assign($assignable)->as($this->assignAlias)->and()->render($this->template);
    }
}

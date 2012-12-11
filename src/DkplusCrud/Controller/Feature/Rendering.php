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
class Rendering extends AbstractFeature
{
    /** @var string */
    protected $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function execute(Event $event)
    {
        return $this->getController()->dsl()->render($this->template);
    }
}

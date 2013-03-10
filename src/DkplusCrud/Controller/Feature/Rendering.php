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
        $event->getViewModel()->setTemplate($this->template);
    }
}

<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event\OutputEvent;

/**
 * Renders the given view script.
 *
 * Can be used only once per Action.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class Rendering extends AbstractFeature
{
    /** @var string */
    protected $eventTypes = self::EVENT_TYPE_OUTPUT;

    /** @var string */
    protected $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function output(OutputEvent $event)
    {
        $event->getViewModel()->setTemplate($this->template);
    }
}

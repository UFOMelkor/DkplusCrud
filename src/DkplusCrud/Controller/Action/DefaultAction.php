<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

/**
 * Useful for most cases.
 *
 * Triggers only the three events.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class DefaultAction extends AbstractAction
{
    public function execute()
    {
        $this->triggerEvent($this->getInputEvent());
        $this->triggerEvent($this->getModelEvent());
        $this->triggerEvent($this->getOutputEvent());

        return $this->getOutputEvent()->getResult();
    }
}

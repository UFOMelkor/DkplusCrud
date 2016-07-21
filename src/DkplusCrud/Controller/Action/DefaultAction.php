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
        $this->triggerEvent('pre');
        $this->triggerEvent('');
        $this->triggerEvent('post');

        return $this->getEvent()->getResult();
    }
}

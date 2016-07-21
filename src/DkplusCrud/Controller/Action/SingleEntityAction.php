<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

/**
 * Use this action when you need to access a single entity like deleting it.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class SingleEntityAction extends AbstractAction
{
    public function execute()
    {
        $this->triggerEvent('pre');

        if (!$this->getEvent()->hasEntity()) {
            $this->triggerEvent('notFound');
            return $this->getEvent()->getResult();
        }

        $this->triggerEvent('');
        $this->triggerEvent('post');

        return $this->getEvent()->getResult();
    }
}

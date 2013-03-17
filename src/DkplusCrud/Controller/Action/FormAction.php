<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

/**
 * Use this action when you need an update form provided by the pre event.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class FormAction extends AbstractAction
{
    public function execute()
    {
        $this->triggerEvent('pre');

        if (!$this->getEvent()->hasForm()) {
            $this->triggerEvent('notFound');
            return $this->getEvent()->getResult();
        }

        $this->triggerEvent('');
        $this->triggerEvent('post');

        return $this->getEvent()->getResult();
    }
}

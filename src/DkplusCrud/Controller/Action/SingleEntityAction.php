<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
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

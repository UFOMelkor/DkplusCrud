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

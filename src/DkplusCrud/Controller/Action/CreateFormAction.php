<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

use RuntimeException;
use Zend\Form\FormInterface as Form;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class CreateFormAction extends AbstractAction
{
    /**
     *
     * If the action is strict, it throws an exception when no form has been get.
     *
     * Otherwise a notFound-Event will be thrown.
     *
     * @var boolean
     */
    protected $strict = true;

    /** @return boolean */
    public function isStrict()
    {
        return $this->strict;
    }

    /** @param boolean $strict */
    public function setStrict($strict)
    {
        $this->strict = (boolean) $strict;
    }

    /** @throws RuntimeException when not getting a valid controller response */
    public function execute()
    {
        $form = $this->getPreEventResult();

        if ($form === null && $this->strict) {
            throw new RuntimeException('pre' . \ucFirst($this->getName()) . ' should result in a form');
        } elseif ($form === null) {
            return $this->getNotFoundEventResult();
        }

        $result = $this->getMainEventResult($form);

        $this->triggerPostEvent($form, $result);

        return $result;
    }

    protected function getPreEventResult()
    {
        return $this->triggerEvent(
            'pre',
            array(),
            array('DkplusCrud\Util\EventResultVerifier', 'isForm')
        );
    }

    /** @throws RuntimeException when not getting a valid controller response */
    protected function getNotFoundEventResult()
    {
        $notFoundResult = $this->triggerEvent(
            'notFound',
            array(),
            array('DkplusCrud\Util\EventResultVerifier', 'isControllerResponse')
        );

        if ($notFoundResult === null) {
            throw new RuntimeException(
                'notFound' . \ucFirst($this->getName()) . ' should result in a valid controller response'
            );
        }
        return $notFoundResult;
    }

    /** @throws \RuntimeException when not getting a valid controller response */
    protected function getMainEventResult(Form $form)
    {
        $result = $this->triggerEvent(
            '',
            array('form' => $form),
            array('DkplusCrud\Util\EventResultVerifier', 'isControllerResponse')
        );

        if ($result === null) {
            throw new RuntimeException(
                $this->getName() . ' should result in a valid controller response'
            );
        }

        return $result;
    }

    protected function triggerPostEvent($form, $result)
    {
        $this->triggerEvent('post', array('form' => $form, 'result' => $result));
    }
}

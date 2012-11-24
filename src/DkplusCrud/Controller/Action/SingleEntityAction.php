<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

use RuntimeException;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class SingleEntityAction extends AbstractAction
{
    /** @throws RuntimeException when not getting a valid controller response */
    public function execute()
    {
        $entity = $this->getPreEventResult();

        if ($entity === null) {
            return $this->getNotFoundEventResult();
        }

        $result = $this->getMainEventResult($entity);

        $this->triggerPostEvent($entity, $result);

        return $result;
    }

    protected function getPreEventResult()
    {
        return $this->triggerEvent(
            'pre',
            array(),
            array('DkplusCrud\Util\EventResultVerifier', 'isNotNull')
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
                'pre' . \ucFirst($this->getName()) . ' should result in a valid controller response'
            );
        }
        return $notFoundResult;
    }

    /** @throws \RuntimeException when not getting a valid controller response */
    protected function getMainEventResult($entity)
    {
        $result = $this->triggerEvent(
            '',
            array('entity' => $entity),
            array('DkplusCrud\Util\EventResultVerifier', 'isControllerResponse')
        );

        if ($result === null) {
            throw new RuntimeException(
                $this->getName() . ' should result in a valid controller response'
            );
        }

        return $result;
    }

    protected function triggerPostEvent($entity, $result)
    {
        $this->triggerEvent('post', array('entity' => $entity, 'result' => $result));
    }
}

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
class MultipleEntitiesAction extends AbstractAction
{
    /** @throws RuntimeException when not getting anything from the preEvent */
    public function execute()
    {
        $entity = $this->getPreEventResult();

        $result = $this->getMainEventResult($entity);

        $this->triggerPostEvent($entity, $result);

        return $result;
    }

    /** @throws RuntimeException when not getting anything from the preEvent */
    protected function getPreEventResult()
    {
        $result =  $this->triggerEvent(
            'pre',
            array(),
            array('DkplusCrud\Util\EventResultVerifier', 'isNotNull')
        );

        if ($result === null) {
            throw new RuntimeException('pre' . \ucFirst($this->getName()) . ' should result in anything not null');
        }

        return $result;
    }

    /** @throws \RuntimeException when not getting a valid controller response */
    protected function getMainEventResult($entities)
    {
        $result = $this->triggerEvent(
            '',
            array('entities' => $entities),
            array('DkplusCrud\Util\EventResultVerifier', 'isControllerResponse')
        );

        if ($result === null) {
            throw new RuntimeException(
                $this->getName() . ' should result in a valid controller response'
            );
        }

        return $result;
    }

    protected function triggerPostEvent($entities, $result)
    {
        $this->triggerEvent('post', array('entities' => $entities, 'result' => $result));
    }
}

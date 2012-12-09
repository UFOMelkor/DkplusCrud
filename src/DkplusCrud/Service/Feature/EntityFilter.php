<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use Doctrine\ORM\QueryBuilder;
use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class EntityFilter extends AbstractFeature
{
    /** @var string */
    protected $eventName = 'queryBuilder';

    /** @var boolean */
    protected $refineResults = true;

    /** @var QueryBuilder */
    protected $queryBuilder;

    /** @var string */
    protected $entityClass;

    public function __construct($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    public function refineResults()
    {
        $this->refineResults = true;
    }

    public function enlargeResults()
    {
        $this->refineResults = false;
    }

    public function execute(Event $event)
    {
        $this->queryBuilder = $event->getParam('queryBuilder');

        if ($this->refineResults) {
            $this->queryBuilder->andWhere('e INSTANCE OF ' . $this->entityClass);
        } else {
            $this->queryBuilder->orWhere('e INSTANCE OF ' . $this->entityClass);
        }
    }
}

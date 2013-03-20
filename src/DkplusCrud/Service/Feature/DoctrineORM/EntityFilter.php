<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature\DoctrineORM;

use DkplusCrud\Service\Feature\AbstractFeature;
use Doctrine\ORM\QueryBuilder;
use Zend\EventManager\EventInterface as Event;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.2.0
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
        $queryBuilder = $event->getParam('queryBuilder');

        if ($this->refineResults) {
            $queryBuilder->andWhere('e INSTANCE OF ' . $this->entityClass);
        } else {
            $queryBuilder->orWhere('e INSTANCE OF ' . $this->entityClass);
        }
    }
}

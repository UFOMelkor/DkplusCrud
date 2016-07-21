<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature;

use Zend\EventManager\EventInterface as Event;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class Order extends AbstractFeature
{
    /** @var string */
    protected $eventName = 'queryBuilder';

    /** @var string */
    private $orderBy;

    /** @var string */
    private $order;

    public function __construct($orderBy, $order = 'ASC')
    {
        $this->orderBy = $orderBy;
        $this->order   = $order;
    }

    public function execute(Event $event)
    {
        $queryBuilder = $event->getParam('queryBuilder');
        /* @var $queryBuilder \Doctrine\ORM\QueryBuilder */
        $queryBuilder->addOrderBy('e.' . $this->orderBy, $this->order);
    }
}

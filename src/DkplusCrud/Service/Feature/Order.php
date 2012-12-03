<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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

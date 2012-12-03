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
class IntersectionFilter extends AbstractFeature
{
    /** @var string */
    protected $eventName = 'queryBuilder';

    /** @var array */
    private $arguments;

    /** @var string */
    private $expression;

    /**
     * @param string $expression eq, between, lt, lte, gt, gte, like
     * @param string $attribute  the attribute to filter
     * @param mixed  $value       All other arguments are filtering values
     */
    public function __construct($expression)
    {
        $this->expression   = $expression;
        $this->arguments    = \func_get_args();
        \array_shift($this->arguments);
        $this->arguments[0] = 'e.' . $this->arguments[0];
    }

    public function execute(Event $event)
    {
        $queryBuilder = $event->getParam('queryBuilder');
        /* @var $queryBuilder \Doctrine\ORM\QueryBuilder */

        $expr = $this->getExpression($queryBuilder);
        $this->addExpression($queryBuilder, $expr);
    }

    protected function getExpression(QueryBuilder $queryBuilder)
    {
        return \call_user_func_array(array($queryBuilder->expr(), $this->expression), $this->arguments);
    }

    protected function addExpression(QueryBuilder $queryBuilder, $expr)
    {
        $queryBuilder->andWhere($expr);
    }
}

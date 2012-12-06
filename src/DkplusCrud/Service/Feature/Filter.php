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
class Filter extends AbstractFeature
{
    /** @var string */
    protected $eventName = 'queryBuilder';

    /** @var array */
    protected $parameters = array();

    /** @var \Doctrine\ORM\Query\Expr\Base[] */
    protected $expressions = array();

    /** @var callback[] */
    protected $queue = array();

    /** @var boolean */
    protected $refineResults = false;

    /** @var QueryBuilder */
    protected $queryBuilder;

    public function refineResults()
    {
        $this->refineResults = true;
    }

    public function enlargeResults()
    {
        $this->refineResults = false;
    }

    public function inArray($attribute, array $values)
    {
        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $values, $filter) {
                $filter->inArray($attribute, $values);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $values;

        $this->expressions[] = $this->queryBuilder->expr()->in(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function like($attribute, $value)
    {
        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $value, $filter) {
                $filter->like($attribute, $value);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $value;

        $this->expressions[] = $this->queryBuilder->expr()->like(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function equals($attribute, $value)
    {
        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $value, $filter) {
                $filter->equals($attribute, $value);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $value;

        $this->expressions[] = $this->queryBuilder->expr()->eq(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function between($attribute, $start, $end)
    {
        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $start, $end, $filter) {
                $filter->between($attribute, $start, $end);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset]       = $start;
        $this->parameters[$attribute . ($parameterOffset + 1)] = $end;

        $this->expressions[] = $this->queryBuilder->expr()->between(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset,
            ':' .$attribute . ($parameterOffset + 1)
        );
    }

    public function lessThanEquals($attribute, $value)
    {
        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $value, $filter) {
                $filter->lessThanEquals($attribute, $value);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $value;

        $this->expressions[] = $this->queryBuilder->expr()->lte(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function lessThan($attribute, $value)
    {

        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $value, $filter) {
                $filter->lessThan($attribute, $value);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $value;

        $this->expressions[] = $this->queryBuilder->expr()->lt(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function greaterThanEquals($attribute, $value)
    {

        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $value, $filter) {
                $filter->greaterThanEquals($attribute, $value);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $value;

        $this->expressions[] = $this->queryBuilder->expr()->gte(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function greaterThan($attribute, $value)
    {
        $filter = $this;
        if ($this->queryBuilder === null) {
            $this->queue[] = function () use ($attribute, $value, $filter) {
                $filter->greaterThan($attribute, $value);
            };
            return;
        }

        $parameterOffset = count($this->queryBuilder->getParameters())
                         + count($this->parameters);

        $this->parameters[$attribute . $parameterOffset] = $value;

        $this->expressions[] = $this->queryBuilder->expr()->gt(
            'e.' . $attribute,
            ':' .$attribute . $parameterOffset
        );
    }

    public function execute(Event $event)
    {
        $this->queryBuilder = $event->getParam('queryBuilder');

        foreach ($this->queue as $callback) {
            \call_user_func($callback);
        }

        foreach ($this->expressions as $expression) {
            if ($this->refineResults) {
                $this->queryBuilder->andWhere($expression);
            } else {
                $this->queryBuilder->orWhere($expression);
            }
        }

        foreach ($this->parameters as $key => $value) {
            $this->queryBuilder->setParameter($key, $value);
        }
    }
}

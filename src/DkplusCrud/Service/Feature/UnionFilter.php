<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Service\Feature;

use Doctrine\ORM\QueryBuilder;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Service\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class UnionFilter extends IntersectionFilter
{
    protected function addExpression(QueryBuilder $queryBuilder, $expr)
    {
        $queryBuilder->orWhere($expr);
    }
}

<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Service\Feature;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.2.0
 */
interface FilterInterface extends FeatureInterface
{
    public function refineResults();

    public function enlargeResults();

    public function inArray($attribute, array $values);

    public function like($attribute, $value);

    public function equals($attribute, $value);

    public function between($attribute, $start, $end);

    public function lessThanEquals($attribute, $value);

    public function lessThan($attribute, $value);

    public function greaterThanEquals($attribute, $value);

    public function greaterThan($attribute, $value);
}

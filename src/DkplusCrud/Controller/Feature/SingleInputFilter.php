<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\Service;

/**
 * Like <code>MultipleInputFilter</code> but using a exactly one input for all filter columns.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class SingleInputFilter extends MultipleInputFilter
{
    /**
     * @param Service  $service
     * @param string[] $properties  The entity properties respectively db columns to compare with.
     * @param string   $input       The name of the input to fetch the comparation value from.
     */
    public function __construct(Service $service, array $properties, $input)
    {
        $propertyInputMap = array();
        foreach ($properties as $property) {
            $propertyInputMap[$property] = $input;
        }

        parent::__construct($service, $propertyInputMap);
    }
}

<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\Service;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class SingleInputFilter extends MultipleInputFilter
{
    public function __construct(Service $service, array $properties, $input)
    {
        $propertyInputMap = array();
        foreach ($properties as $property) {
            $propertyInputMap[$property] = $input;
        }

        parent::__construct($service, $propertyInputMap);
    }
}

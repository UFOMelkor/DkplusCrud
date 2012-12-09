<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\Service;
use DkplusCrud\Service\Feature\Filter as ServiceFilter;
use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
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

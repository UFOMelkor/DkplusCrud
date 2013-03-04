<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller;

use \RuntimeException;

/**
 * Thrown if a feature has been executed for that not all needed parameters are available.
 *
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class ConfigurationError extends RuntimeException
{
    /**
     * @param string $eventName The name of the event where the error occured.
     * @param string $missedParam The parameter that was needed but missed.
     */
    public function __construct($eventName, $missedParam)
    {
        parent::__construct(\sprintf('On “%s” there should be a parameter “%s”', $eventName, $missedParam));
    }
}

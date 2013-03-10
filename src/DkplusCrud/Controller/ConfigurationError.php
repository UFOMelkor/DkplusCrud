<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller;

use \RuntimeException;

/**
 * Thrown when a feature has been executed for that not all needed parameters are available.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
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

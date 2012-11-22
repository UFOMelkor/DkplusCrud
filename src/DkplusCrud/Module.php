<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Module
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface as AutoloaderProvider;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Module
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class Module implements AutoloaderProvider
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            )
        );

    }
}

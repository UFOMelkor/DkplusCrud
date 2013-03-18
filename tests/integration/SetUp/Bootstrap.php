<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Integration\SetUp;

use DkplusCrud\Controller\Controller;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class Bootstrap
{
    /** ServiceManager */
    protected static $serviceManager;

    /** @return ServiceManager */
    public static function getServiceManager()
    {
        self::initServiceManager();
        return self::$serviceManager;
    }

    protected static function initServiceManager()
    {
        if (self::$serviceManager != null) {
            return;
        }

        $config = include __DIR__ . '/application.config.php';

        self::$serviceManager = Application::init($config)->getServiceManager();
    }

    /** @return \Zend\Mvc\Controller\PluginManager */
    public static function getControllerPluginManager()
    {
        return self::getServiceManager()->get('ControllerPluginManager');
    }

    public static function injectControllerSetUp(Controller $controller)
    {
        $controller->setServiceLocator(self::getServiceManager());
        $controller->setPluginManager(self::getControllerPluginManager());
    }
}

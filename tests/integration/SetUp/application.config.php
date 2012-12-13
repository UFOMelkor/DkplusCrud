<?php
/**
 * @category   DkplusIntegration
 * @package    Crud
 * @subpackage SetUp
 * @author     Oskar Bley <oskar@programming-php.net>
 */
return array(
    'modules' => array(
        'DkplusControllerDsl',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/*.config.php',
        ),
        'module_paths' => array(
            __DIR__ . '/../../../vendor',
        ),
    ),
);

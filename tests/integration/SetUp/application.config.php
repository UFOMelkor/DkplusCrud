<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */
return array(
    'modules' => array(
        'DkplusBase',
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

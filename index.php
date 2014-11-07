<?php
/** Set correct relative base path */
define('__SAMSON_BASE__', '/'.basename(__DIR__).'/');

/** Set current directory as project root */
define('__SAMSON_CWD__', __DIR__.'/');

/** Load composer autoloader */
require('vendor/autoload.php');

/** Automatic parent web-application configuration read */
if (file_exists('app/config')) {
    // Read all configuration files
    foreach(\samson\core\File::dir('app/config') as $file) {
        // If this is supported module configuration
        if (stripos('Compressor, Deploy, ActiveRecord', basename($file)) !== false) {
            require($file);
        }
    }
}

/** Load SamsonCMS **/
require('www/index.php');
<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

/** Set current directory as project root */
if (!defined('__SAMSON_CWD__')) {
    define('__SAMSON_CWD__', dirname(__DIR__) . '/');
}

/** Set default locale to - Russian */
if (!defined('DEFAULT_LOCALE')) {
    define('DEFAULT_LOCALE', 'ru');
}

/** Require composer autoloader */
if (!class_exists('samson\core\Core')) {
    require_once('../vendor/autoload.php');
}

/** Automatic parent web-application configuration read */
if (file_exists('../../../app/config')) {
    // Read all configuration files
    foreach(\samson\core\File::dir('app/config') as $file) {
        // If this is supported module configuration
        if (stripos('Compressor, Deploy, ActiveRecord', basename($file)) !== false) {
            require($file);
        }
    }
}

// Set supported locales
setlocales('en', 'ua', 'ru');

// Start SamsonPHP application
s()->composer()
    ->subscribe('core.e404','e404')
    ->subscribe('core.routing', array(url(),'router'));

// Iterate all external applications if present
if(isset($applications)) {
    foreach($applications as $application) {
        s()->load($application);
    }
}

s()->start('main');

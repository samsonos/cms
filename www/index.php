<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

/** Set default locale to - Russian */
if (!defined('DEFAULT_LOCALE')) {
    define('DEFAULT_LOCALE', 'ru');
}

/** Require composer autoloader */
if (!defined('VENDOR_PATH')) {
    require_once('../vendor/autoload.php');
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

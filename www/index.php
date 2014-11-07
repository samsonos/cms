<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

//[PHPCOMPRESSOR(remove,start)]
/** If we run cms as independent web-application */
if (!defined('VENDOR_PATH')) {
    // Define path to vendor folder
    define('VENDOR_PATH', '../vendor/');

    /** Set default locale to - Russian */
    define('DEFAULT_LOCALE', 'ru');
}
//[PHPCOMPRESSOR(remove,end)]

/** Require composer autoloader */
require_once(VENDOR_PATH.'autoload.php');

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

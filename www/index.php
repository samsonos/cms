<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

//[PHPCOMPRESSOR(remove,start)]
/** If we run cms as independent web-application */
if (!defined('__PATH')) {
    /** Build absolute path to SamsonCMS root folder */
    define('__PATH', __DIR__.'/../');

    /** Set SamsonCMS text environment */
    define('__SAMSONCMS_INDEPENDENT', true);

    /** Set default locale to - Russian */
    define('DEFAULT_LOCALE', 'ru');
}
//[PHPCOMPRESSOR(remove,end)]

/** Require composer autoloader */
require('../vendor/autoload.php');

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

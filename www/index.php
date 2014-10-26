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

    /** Set new project structure vendor path */
    define('__SAMSON_VENDOR_PATH', __PATH.'vendor/');

    /** Set SamsonCMS text environment */
    define('__SAMSONCMS_INDEPENDENT', true);

    /** Set default locale to - Russian */
    define('DEFAULT_LOCALE', 'ru');
}
//[PHPCOMPRESSOR(remove,end)]

/** Require composer autoloader */
require(__SAMSON_VENDOR_PATH.'/autoload.php');


/** Check if this is independent mode */
if (defined('__SAMSONCMS_INDEPENDENT')) {
    /** Load generic ActiveRecord config to start application in test mode */
    require(__PATH.'src/ActiveRecordConfig.php');
}

// Set supported locales
setlocales('en', 'ua', 'ru');

// Start SamsonPHP application
s()->composer()
    ->load(__PATH.'src/formcontainer')
    ->load(__PATH.'src/gallery')
    ->load(__PATH.'src/input')
    ->load(__PATH.'src/field/date')
    ->load(__PATH.'src/field/select')
    ->load(__PATH.'src/field/uploadfile')
    ->load(__PATH.'src/field/uploadimg')
    ->load(__PATH.'src/field/wysiwyg')
    ->load(__PATH.'src/apps/gallery')
    ->load(__PATH.'src/apps/user')
    ->subscribe('core.e404','e404')
    ->subscribe('core.routing', array(url(),'router'));

// Iterate all external applications if present
if(isset($applications)) {
    foreach($applications as $application) {
        s()->load($application);
    }
}

s()->start('main');

<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

/** Build absolute path to SamsonCMS root folder */
if (!defined('__PATH')) {
    define('__PATH', __DIR__.'/../');
}

/** Check if core is already loaded */
if (!defined('__SAMSON_VENDOR_PATH')) {
    /** Set new project structure vendor path */
    define('__SAMSON_VENDOR_PATH', __PATH.'vendor/');

    /** Set SamsonCMS text environment */
    define('__SAMSONCMS_TESTMODE', true);

    /** Set default locale to - Russian */
    define('DEFAULT_LOCALE', 'ru');

    /** Load SamsonPHP framework */
    require(__SAMSON_VENDOR_PATH.'/autoload.php');

    /** Load generic ActiveRecord config to start application in test mode */
    require(__PATH.'src/ActiveRecordConfig.php');

} else { // CMS is ran from other web-application
    /** Load SamsonPHP framework */
    require(__SAMSON_VENDOR_PATH.'/autoload.php');
}

/** Collection of WRONG module namespaces resolving */
/*samson\core\AutoLoader::$moduleMap = array(
    'samson\cms\table' => __SAMSON_VENDOR_PATH.'samsonos/cms_table/'
);*/

// Set supported locales
setlocales('en', 'ua', 'ru');

// Start SamsonPHP application
s()->composer()
    ->load(__PATH.'src/app')
    ->load(__PATH.'src/ajaxloader')
    ->load(__PATH.'src/fixedheader')
    ->load(__PATH.'src/formcontainer')
    ->load(__PATH.'src/tabs')
    ->load(__PATH.'src/treeview')
    ->load(__PATH.'src/gallery')
    ->load(__PATH.'src/translit')
    ->load(__PATH.'src/input')
    ->load(__PATH.'src/field/date')
    ->load(__PATH.'src/field/select')
    ->load(__PATH.'src/field/uploadfile')
    ->load(__PATH.'src/field/uploadimg')
    ->load(__PATH.'src/field/wysiwyg')
    ->load(__PATH.'src/apps/material')
    ->load(__PATH.'src/apps/navigation')
    ->load(__PATH.'src/apps/field')
    ->load(__PATH.'src/apps/gallery')
    ->load(__PATH.'src/apps/user')
    ->load(__PATH.'src/apps/help')
    ->subscribe('core.e404','e404')
    ->subscribe('core.routing', array(url(),'router'));

// Iterate all external applications if present
if(isset($applications)) {
    foreach($applications as $application) {
        s()->load($application);
    }
}

s()->start('main');

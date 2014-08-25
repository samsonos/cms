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
if (!function_exists('s')) {

    /** Set SamsonCMS text environment */
    define('__SAMSONCMS_TESTMODE', true);

    /** Load SamsonPHP framework */
    require(__SAMSON_VENDOR_PATH.'/samsonos/php_core/samson.php');

    /** Load generic ActiveRecord config to start application in test mode */
    require(__PATH.'src/ActiveRecordConfig.php');
}

/** Collection of WRONG module namespaces resolving */
samson\core\AutoLoader::$moduleMap = array(
    'samson\cms\table' => __SAMSON_VENDOR_PATH.'samsonos/cms_table/'
);

// Set supported locales
setlocales('en', 'ru');

// Start SamsonPHP application
s()->composer()
    ->e404('e404')
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
    ->start('main');

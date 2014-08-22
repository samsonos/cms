<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

/** If no vendor path is specified already */
if (!defined('__SAMSON_VENDOR_PATH')) {
    /** Set new project structure vendor path */
    define('__SAMSON_VENDOR_PATH', '../vendor/');

    /** Set SamsonCMS text environment */
    define('__SAMSONCMS_TESTMODE', true);

    /** Load SamsonPHP framework */
    require(__SAMSON_VENDOR_PATH.'/samsonos/php_core/samson.php');

    /** Load generic ActiveRecord config to start application in test mode */
    require('../src/ActiveRecordConfig.php');
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
    ->load('../src/app')
    ->load('../src/ajaxloader')
    ->load('../src/fixedheader')
    ->load('../src/formcontainer')
    ->load('../src/tabs')
    ->load('../src/treeview')
    ->load('../src/translit')
    ->load('../src/input')
    ->load('../src/field/date')
    ->load('../src/field/select')
    ->load('../src/field/uploadfile')
    ->load('../src/field/uploadimg')
    ->load('../src/field/wysiwyg')
    ->load('../src/apps/material')
    ->load('../src/apps/help')
    ->load('../src/apps/navigation')
    ->load('../src/apps/field')
    ->load('../src/apps/gallery')
    ->load('../src/apps/user')
    ->start('main');

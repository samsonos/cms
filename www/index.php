<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

/** If no vendor path is specified already */
if (!defined('__SAMSON_VENDOR_PATH')) {
    /** Set new project structure vendor path */
    define('__SAMSON_VENDOR_PATH', '../vendor/');

    /** Load SamsonPHP framework */
    require(__SAMSON_VENDOR_PATH.'/samsonos/php_core/samson.php');
}

// Set supported locales
setlocales('en', 'ru');

// Start SamsonPHP application
s()->composer()
    ->load('../src/ajaxloader')
    ->load('../src/app/gallery')
    ->load('../src/app/fixedheader')
    ->load('../src/app/formcontainer')
    ->load('../src/app/tabs')
    ->load('../src/app/treeview')
    ->load('../src/app/translit')
    ->load('../src/input')
    ->load('../src/field/date')
    ->load('../src/field/select')
    ->load('../src/field/uploadfile')
    ->load('../src/field/uploadimg')
    ->load('../src/field/wysiwyg')
    ->load('../src/field/field')
    ->load('../src/app/material')
    ->load('../src/app/help')
    ->load('../src/app/navigation')
    ->load('../src/app/field')
    ->load('../src/app/gallery')
    ->load('../src/app/user')
    ->start('main');

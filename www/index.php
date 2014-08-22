<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

/**
 * DEFINE CURRENT VENDOR PATH
 *
 * Every SamsonPHP web application is scalable and provides ability
 * to choose vendor folder path. This gives ability to use single location
 * for all vendor modules across projects. Also this constant is configured
 * by configuration cascade, if it's declared earlier then it won't be
 * declared here.
 */
if(!defined('__SAMSON_VENDOR_PATH')) {
    define('__SAMSON_VENDOR_PATH', '/var/www.prod/vendor/');
}

// Set supported locales
setlocales('en', 'ru');

// Custom module location map
\samson\core\AutoLoader::$moduleMap = array(
    'samson\cms\table'         => __SAMSON_VENDOR_PATH.'samsonos/cms/table/',
    'samson\cms\web\material'  => __SAMSON_VENDOR_PATH.'samsonos/cms/material/',
    'samson\cms\web\navigation'=> __SAMSON_VENDOR_PATH.'samsonos/cms/navigation/',
    'samson\cms\web\field'     => __SAMSON_VENDOR_PATH.'samsonos/cms/field/',
    'samson\cms\web\gallery'   => __SAMSON_VENDOR_PATH.'samsonos/cms/gallery/',
    'samson\cms\web\user'      => __SAMSON_VENDOR_PATH.'samsonos/cms/user/',
    'samson\cms'               => __SAMSON_VENDOR_PATH.'samsonos/cms/api/',
);

// Start SamsonPHP application
s()->composer()->start('main');

<?php
/** Set new project structure vendor path */
define('VENDOR_PATH', __PATH.'vendor/');

/** Set correct relative base path */
define('__SAMSON_BASE__', '/'.basename(__DIR__).'/');

/** Load composer autoloader */
require(VENDOR_PATH.'autoload.php');

/** Configuration section */

/** Load SamsonCMS **/
require(__PATH.'www/index.php');
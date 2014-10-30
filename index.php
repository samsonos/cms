<?php
/** Define path SamsonCMS project */
define('__PATH', '');

/** Set new project structure vendor path */
define('__SAMSON_VENDOR_PATH', __PATH.'vendor/');

/** Set correct relative base path */
define('__SAMSON_BASE__', '/'.basename(__DIR__).'/');

/** Set correct cache path */
define('__SAMSON_CACHE_PATH', 'www/cache');

/** Load composer autoloader */
require(__SAMSON_VENDOR_PATH.'autoload.php');

/** Configuration section */

/** Load SamsonCMS **/
require(__PATH.'www/index.php');
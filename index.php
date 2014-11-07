<?php
/** Set correct relative base path */
define('__SAMSON_BASE__', '/'.basename(__DIR__).'/');

/** Load composer autoloader */
require('vendor/autoload.php');

/** Configuration section */
// require('../app/config/YourConfigurationConfig.php');

/** Load SamsonCMS **/
require('www/index.php');
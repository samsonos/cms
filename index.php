<?php
/** Define path SamsonCMS project */
define('__PATH', '');

/** Set new project structure vendor path */
define('__SAMSON_VENDOR_PATH', __PATH.'vendor/');

/** Load composer autoloader */
require(__SAMSON_VENDOR_PATH.'autoload.php');

/** @var array $applications Collection of external SamsonCMS applications */
$applications = array(
    '../../RemainsApp/' /** Remains SamsonCMS application configuration */
);

// Manually tell where all application classes are located
\samson\core\AutoLoader::$moduleMap = array(
    'samson\karnaval\remains' => '../../RemainsApp/',
    'samson\cms\table' => __SAMSON_VENDOR_PATH.'/samsonos/cms_table/',
    'samson\parse' => '../../vendor/samsonos/php_parse/',
);

/**
 * Configuration requirements
 *
 * Here we must include all configuration classes that will be used by
 * SamsonCMS
 */
require('../app/config/ActiveRecordConfig.php');
require('../app/config/ActiveRecordProdConfig.php');
require('../app/config/CompressorConfig.php');
require('../app/config/DeployConfig.php');

/** Load SamsonCMS **/
require(__PATH.'www/index.php');
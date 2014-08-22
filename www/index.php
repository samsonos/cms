<?php
/**
 * SamsonCMS Init script
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */

// Set supported locales
setlocales('en', 'ru');

// Custom module location map
\samson\core\AutoLoader::$moduleMap = array(
    'samson\cms\web\material'  => '../src/app/material/',
    'samson\cms\web\navigation'=> '../src/app/navigation/',
    'samson\cms\web\field'     => '../src/app/field/',
    'samson\cms\web\gallery'   => '../src/app/gallery/',
    'samson\cms\web\user'      => '../src/app/user/',
    'samson\cms\input'         => '../src/field/',
);

// Start SamsonPHP application
s()->composer()->start('main');

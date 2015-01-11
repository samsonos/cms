<?php
namespace cms;

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 19.08.14 at 16:05
 */
if (!defined('EXTERNAL_CONFIG')) {
    /** Test ActiveRecord configuration for development */
    class CompressorConfig extends \samson\core\Config
    {
        public $output = '/var/www.final/cms.dev/www/';
    }
}

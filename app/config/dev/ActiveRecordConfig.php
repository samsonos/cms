<?php
namespace cms\dev;
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 19.08.14 at 16:05
 */
if (!defined('EXTERNAL_CONFIG')) {
    /** Test ActiveRecord configuration for development */
    class ActiveRecordConfig extends \samson\core\Config
    {
        public $name = 'yourtour';
        public $login = 'samsonos';
        public $pwd = 'AzUzrcVe4LJJre9f';
    }
}

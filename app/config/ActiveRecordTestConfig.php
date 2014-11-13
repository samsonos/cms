<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 19.08.14 at 16:05
 */
if (!defined('EXTERNAL_CONFIG')) {
    /** Test ActiveRecord configuration for development */
    class ActiveRecordTestConfig extends \samson\core\Config
    {
        public $__module = 'activerecord';

        public $name = 'landscape-test';
        public $login = 'nazarenko';
        public $pwd = 'AN12345!';
    }
}

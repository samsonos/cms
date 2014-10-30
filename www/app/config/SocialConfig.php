<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 12:20
 */

class SocialConfig extends \samson\core\Config
{
    public $__module = 'social';

    public $dbTable = '\samson\activerecord\user';

    public $hashAlgorithm = 'md5';

    public $hashLength = 32;
}
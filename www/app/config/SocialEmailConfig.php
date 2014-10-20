<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 12:21
 */

/** Конфигурация для SocialEmail */
class SocialEmailConfig extends \samson\core\Config
{
    public $__module = 'socialemail';

    public $hashAlgorithm = 'md5';

    public $hashLength = 32;

    public $dbHashEmailField = 'md5_email';

    public $dbHashPasswordField = 'md5_password';

    public $dbConfirmField = 'confirmed';
}
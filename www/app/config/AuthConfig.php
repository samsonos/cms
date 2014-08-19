<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 19.08.14 at 16:37
 */

/** Конфигурация для Auth */
class AuthCMSConfig extends samson\core\Config
{
    public $__module = 'auth2';
    public $entity 	= 'user';
    public $force = true;
}

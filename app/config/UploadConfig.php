<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 17.11.2014
 * Time: 16:15
 */
namespace samsonos\cms;

use samson\core\Config;

/** Upload module configuration */
class UploadConfig extends Config
{
    /** @var string Configured module identifier */
    public $__module = 'samsonupload';

    /** @var callback Path builder */
    public $uploadDirHandler = array('samsonos\cms\UploadConfig', 'createPath');

    /**
     * Generic upload path builder
     * @return string Upload path
     */
    public static function createPath()
    {
        return 'upload';
    }
}

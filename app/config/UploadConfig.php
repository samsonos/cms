<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 17.11.2014
 * Time: 16:15
 */

/** Upload module configuration */
class UploadConfig extends \samson\core\Config
{
    /** @var string Configured module iidentifier */
    public $__module = 'samsonupload';

    /** @var callback Path builder */
    public $uploadDirHandler = array(__CLASS__, 'createPath');

    /**
     * Generic upload path builder
     * @param string $materialID Uploaded material identifier
     * @return string Upload path
     */
    public static function createPath($materialID)
    {
        return 'upload';
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 17.11.2014
 * Time: 16:15
 */

class UploadConfig extends \samson\core\Config
{
    public $__module = 'samsonupload';

    public $handler = array('\samson\cms\web\upload\UploadHandler', 'createPath');
}
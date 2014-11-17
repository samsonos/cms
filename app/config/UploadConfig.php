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

    public $adapterType = 'amazon';

    public $accessKey = 'AKIAJRG2YUZ7KGMLDXRQ';

    public $secretKey = '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';

    public $bucket = 'landscapestatic';

    public $awsUrl = 'http://static.landscape.ua';

    public $handler = array('\samson\cms\web\upload\UploadHandler', 'createPath');
}
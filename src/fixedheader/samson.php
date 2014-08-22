<?php
namespace samson\js\fixedheader;

use \samson\core\CompressableExternalModule;

/**
 * Интерфейс для подключения модуля в ядро фреймворка SamsonPHP
 *
 * @package SamsonPHP
 * @author Vitaly Iegorov <vitalyiegorov@gmail.com>
 * @author Nikita Kotenko <nick.w2r@gmail.com>
 * @version 0.1
 */
class FixedHeader extends \samson\core\CompressableExternalModule
{	
	/** Module dependencies */
	protected $requirements = array('SamsonJS');
}
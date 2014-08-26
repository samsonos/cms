<?php
namespace samson\js\translit;

/**
 * Интерфейс для подключения модуля в ядро фреймворка SamsonPHP
 *
 * @package SamsonPHP
 * @author Vitaly Iegorov <vitalyiegorov@gmail.com>
 * @author Nikita Kotenko <nick.w2r@gmail.com>
 * @version 0.1
 */
class Translit extends \samson\core\CompressableExternalModule
{	
	/** Identifier */
	protected $id = 'translit';	
	
	/** Module dependencies */
	protected $requirements = array('SamsonJS');
}
<?php
namespace samson\js\tabs;

/**
 * Интерфейс для подключения модуля в ядро фреймворка SamsonPHP
 *
 * @package SamsonPHP
 * @author Vitaly Iegorov <vitalyiegorov@gmail.com>
 * @author Nikita Kotenko <nick.w2r@gmail.com>
 * @version 0.1
 */
class Tabs extends \samson\core\CompressableExternalModule
{	
	/** Identifier */
	protected $id = 'tabs';	
	
	/** Module dependencies */
	protected $requirements = array('SamsonJS');
}
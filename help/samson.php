<?php
namespace samson\cms\help;

class HelpApplication extends \samson\cms\App
{
	/**
	 * Имя приложения
	 * @var string
	 */
	public $app_name = 'Помощь';
	
	/**
	 * Идентификатор 
	 * @var string
	 */
	protected $id = 'help';
	
	/**
	 * Связи модуля
	 * @var array
	 */
	protected $requirements = array
	(
		'ActiveRecord'
	);	
	
}
?>
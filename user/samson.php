<?php
namespace samson\cms\user;

class UserApplication extends \samson\cms\App
{
	/**
	 * Имя приложения
	 * @var string
	 */
	public $app_name = 'Пользователи';
	
	/** Hide application access from main menu */
	public $hide =  false;
	
	/**
	 * Идентификатор 
	 * @var string
	 */
	protected $id = 'user';
	
	/**
	 * Связи модуля
	 * @var array
	 */
	protected $requirements = array
	(
		'ActiveRecord'
	);
}
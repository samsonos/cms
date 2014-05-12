<?php
class SCMSCommentConnector extends SCMSConnector
{
	/**
	 * Имя приложения
	 * @var string
	 */
	public $app_name = 'Комментарии';
	
	/**
	 * Идентификатор 
	 * @var string
	 */
	protected $id = 'comment';
	
	/**
	 * Связи модуля
	 * @var array
	 */
	protected $requirements = array
	(
		'ActiveRecord',
		'Material'
	);
}
?>
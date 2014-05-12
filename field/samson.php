<?php 
namespace samson\cms\field;

class FieldApplication extends \samson\cms\App
{	
	public $app_name = 'Доп. поля';		
	
	protected $id = 'field';	
	
	/** Hide application access from main menu */
	public $hide = true;
	
	protected $requirements = array
	(
		'ActiveRecord'
	);	
}
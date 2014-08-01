<?php
namespace samson\cms\field;

use samson\cms\input\Field;
use samson\activerecord\dbQuery;
use samson\pager\Pager;

/**
 * Class for rendering SamsonCMS Field table
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class Table extends \samson\cms\table\Table
{
	/** Constructor */
	public function __construct( Pager & $pager = null )
	{
		// Prepare db query
		$this->query = dbQuery( 'samson\cms\cmsfield')
			->join('samson\cms\cmsnavfield')
			->join('samson\cms\cmsnav')
			->order_by('FieldID', 'ASC');	

		// Constructor tree
		parent::__construct( $this->query, $pager ); 
	}
	
	/** @see \samson\cms\site\Table::row() */
	public function row( & $db_row, Pager & $pager = null )
	{ 				
		$type_array = array( 
			0=>'Текст', 				
			1=>'Ресурс',
			4=>'Select', 
			5=>'Таблицы', 
			3=>'Дата', 
			6=>'Материал', 
			7=>'Число',
			8=>'Текстовый редактор'
		);
		
		// Render field row
		return m()
			->set( $db_row->onetoone['_structure'] )
			->set( $db_row, 'field' )
			->set( $pager )
		->output('table/row/index');
	}
}
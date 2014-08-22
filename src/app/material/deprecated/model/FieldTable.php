<?php
namespace samson\cms\material;

use samson\cms\input\Field;

use samson\activerecord\dbQuery;
use samson\pager\Pager;

/**
 * Class for rendering SamsonCMS Field table
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class FieldTable extends \samson\cms\table\Table
{
	/** Change table template */
	public $table_tmpl = 'fieldtable/tmpl';
	
	/** Default table row template */
	public $row_tmpl = 'fieldtable/row/index';
	
	/**
	 * Constructor 
	 * @param unknown $material_id
	 * @param unknown $structure_id
	 * @param Pager $pager
	 */
	public function __construct( $material_id, $structure_id = 0, $locale = '', Pager & $pager = null )
	{
		// Prepare db query
		$this->query = dbQuery( 'samson\cms\cmsnavfield')		
			->join('samson\cms\cmsmaterialfield')
			->join('samson\cms\cmsmaterial')
			->join('samson\cms\cmsfield')
			->order_by('FieldID', 'ASC')
			//->StructureID( $structure_id )
			->cond('material_MaterialID', $material_id )
			->cond('materialfield_locale', $locale );

		// Constructor tree
		parent::__construct( $this->query, $pager ); 
	}
	
	/** @see \samson\cms\site\Table::row() */
	public function row( & $db_row, Pager & $pager = null )
	{ 	
		// Get field metadata		
		$db_field = & $db_row->onetoone['_field'];
		
		// Get material-field data 
		$db_mf = $db_row->onetoone['_materialfield'];		
		
		// Create input element for field
		$input = null;
		switch( $db_field->Type )
		{
			case '4': $input = Field::fromObject( $db_mf, 'Value', 'Select' )->optionsFromString( $db_field->Value ); 	break;
			case '1': $input = Field::fromObject( $db_mf, 'Value', 'File' );	break;
			case '3': $input = Field::fromObject( $db_mf, 'Value', 'Date' );	break;
			case '7': $input = Field::fromObject( $db_mf, 'numeric_value', 'Field' );	break;
			case '8': return false;	break;
			default: $input = Field::fromObject( $db_mf, 'Value', 'Field' ); 		 
		}	
		
		
		// Render field row
		return m()
			->set( $input )
			->set( 'fieldname', isset($db_field->Description{0}) ? $db_field->Description : $db_field->Name )
			->set( $db_field, 'field' )			
			->set( $pager )
		->output( $this->row_tmpl );
	}
}
<?php
namespace samson\cms\input;

/**
 * Generic SamsonCMS input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 *
 */
class Field extends \samson\core\CompressableExternalModule implements \samson\core\iModuleViewable
{		
	/** Database object classname which connected  with this field */
	protected $entity;
	
	/** Database object field name */
	protected $param;
	
	/** Database object current field value */
	protected $value;
	
	/** Main field action controller */
	protected $action = 'save';
	
	/** Special CSS classname for nested field objects to bind JS and CSS */
	protected $cssclass = '__textarea';
	
	/** 
	 * Pointer to database object instance 
	 * @var \samson\activerecord\dbRecord
	 * @see \samson\activerecord\dbRecord 
	 */
	protected $obj;
	
	/** Path to view file for field rendering */
	protected $default_view = "index";
	
	/** Path to view file for inner field rendering */
	protected $field_view = "field";
	
	/**
	 * Create instance of field from metadata
	 * @param unknown $entity
	 * @param unknown $param
	 * @param unknown $identifier
	 */
	public static function & fromMetadata( $entity, $param, $identifier, $classname = __CLASS__ )
	{
		$o = null;
		
		// Correct namespace classname generation 
		$entity = ns_classname( $entity, __NAMESPACE__ );		
		if( !class_exists( $entity ) ) e('Cannot create ## object instance - Class ## does not exists', E_SAMSON_CORE_ERROR, array(__CLASS__, $entity) );
					
		// Generate correct namespace for class
		$classname = uni_classname(ns_classname( strtolower($classname), __NAMESPACE__ ));				
		
		// Try to get field module instance from core
		if( null !== ($f = & m( $classname )) )
		{			
			// Try to find field corresponding entity
			if( dbQuery( $entity )->id( $identifier )->first( $obj ) )
			{		
				// Create field object copy
				$o = & $f->copy();
				$o->view_path = $f->view_path;
				$o->entity	= $entity;
				$o->obj 	= & $obj;
				$o->param 	= $param;
				$o->value 	= $obj[ $param ];
			}
			else e('Cannot create ## object instance - Entity ## with id: ## - does not exists', E_SAMSON_CORE_ERROR, array(__CLASS__, $entity, $identifier) );
		}
		else e('Cannot create ## object instance - Field module ## not loaded to system core', E_SAMSON_CORE_ERROR, array(__CLASS__,$classname) );
		
		return $o;		
	}
	
	/**
	 * Create instance from object 
	 * @param mixed 	$obj 	Object for creating inputfield
	 * @param string 	$param	Object field name
	 * @return \samson\cms\input\InputField Class instance
	 */
	public static function & fromObject( & $obj, $param, $classname = __CLASS__ )
	{			
		$o = null;
		
		// If object is passed
		if( !is_object( $obj ) ) e('Cannot create ## object instance - no object is passed', E_SAMSON_CORE_ERROR, __CLASS__ );

		// Generate correct namespace for class
		$classname = \samson\core\AutoLoader::oldClassName(__NAMESPACE__.'\\'.$classname );

		// Try to get field module instance from core
		if( null !== ($f = & m( $classname )) )
		{	
			// Create input field instance		
			$o = & $f->copy();
			$o->view_path = $f->view_path;
			$o->entity	= get_class($obj);
			$o->obj 	= & $obj;
			$o->param 	= $param;
			$o->value 	= $obj[ $param ];			
		}	
		else e('Cannot create ## object instance - Field module ## not loaded to system core', E_SAMSON_CORE_ERROR, array(__CLASS__,$classname) );
		
		return $o;
	}

    /**
     * Special function for processing value before saving
     */
    public function numericValue($input)
    {
        return $input;
    }
	
	// Controller 
	
	/**
	 * Save input field value
	 * @param mixed $value Field value
	 */
	public function __save()
	{
        elapsed('sdfsd');
		// Does it nessessar?
		s()->async(true);	

		// If we have post data
		if( isset($_POST) )
		{
			// Make pointers to posted parameters
			$entity = & $_POST['__entity'];
			$param 	= & $_POST['__param'];
			$id 	= & $_POST['__obj_id'];
			$value 	= & $_POST['__value'];
			
			// Check if all nessesarly data is passed
			if( !isset( $value ))	return e('CMSField - no "value" is passed for saving', E_SAMSON_CORE_ERROR);
			if( !isset( $entity )) 	return e('CMSField - no "entity" is passed for saving', E_SAMSON_CORE_ERROR);
			if( !isset( $id )) 		return e('CMSField - no "object identifier" is passed for saving', E_SAMSON_CORE_ERROR);
			if( !isset( $param )) 	return e('CMSField - no "object field name" is passed for saving', E_SAMSON_CORE_ERROR);
			
			// Try to find passed object for saving
			if( dbQuery( $entity )->id( $id )->first( $obj ) )
			{
				// Set field value
				$obj[ $param ] = $value;

                // If object supports numeric value
                if ($param != 'numeric_value' && isset($obj['numeric_value'])) {
                    // Convert value to numeric value
                    $obj['numeric_value'] = $this->numericValue($value);
                }

				// Save object
				$obj->save();
			}
			else e('CMSField - Entity ## with id: ## - does not exists', E_SAMSON_CORE_ERROR, array( $entity, $identifier) ); 
		}	
	}
	
	// Logic
	
	/**
	 * Save input field value
	 * @param mixed $value Field value
	 */
	public function save( $value )
	{		
		// Set field value
		$this->obj[ $this->param ] = $value;
		
		// Save object
		$this->obj->save();
	}		
	
	/** @see \samson\core\iModuleViewable::toView() */
	public function toView( $prefix = NULL, array $restricted = array() )
	{	
		// Generate unique prefix if not passed
		$inner_prefix = 'field_';
		
		// Result view collection
		$result = array();
		
// 		// Get only this class properties
// 		$own = array_keys(get_object_vars($this));
// 		$parent = array_keys(get_object_vars( ns_classname('ExternalModule','samson\core')));		
// 		$properties = array_diff( $own, $parent );
			
		// Iterate object vars and gather them in collection	
		foreach ( get_object_vars($this) as $k => $v ) $result[ $inner_prefix.$k ] = $v;			
		
		// Generate field action controller
		$result[ $inner_prefix.'action' ] = url_build( $this->id, $this->action );//'samson_cms_input_field'	
		
		// Generate unique textarea id
		$result[ $inner_prefix.'textarea_id' ] = 'field_'.$this->obj->id;
		
		// Render inner field view
		$result[ $inner_prefix.'view' ] = $this->set($result)->output( $this->field_view );
				
		// Return input fields collection prepared for module view
		return array( 
			$prefix.'html' => $this
				->set( $result )
				->object($this->obj)
			->output( $this->default_view )
		);		
	}	
}
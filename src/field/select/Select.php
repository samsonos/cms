<?php
namespace samson\cms\input;

/**
 * Select SamsonCMS input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 */
class Select extends Field 
{		
	/** Special CSS classname for nested field objects to bind JS and CSS */
	protected $cssclass = '__select';
	
	/** Select options */
	protected $options = '';	
	
	/**
	 * Parse string into select options
	 * 
	 * @param string $string 			Input string
	 * @param string $group_separator 	Separator string for groups  
	 * @param string $view_separator	Separator string for view/value	 
	 * @return samson\cms\input\select\Select Chaining
	 */
	public function optionsFromString( $string, $group_separator = ',', $view_separator = ':' )
	{
		// Clear options data
		$this->options = array();
		
		// Split string into groups and iterate them
		foreach ( explode( $group_separator, $string ) as $group) 
		{
			// Split group into view -> value
			$group = explode( $view_separator, $group );
			
			// Get value
			$value = $group[0];
			
			// Get view or set value
			$view = isset( $group[1] ) ? $group[ 1 ] : $group[ 0 ]; 
			
			// Add option
			$this->options[ $value ] = $view;
		}
		
		// Transform field value to normal view
		$this->value = isset( $this->options[ $this->value ] ) ? $this->options[ $this->value ] : $this->value; 
		
		// Generate options html
		$html_options = '';
		foreach ( $this->options as $k => $v )
		{
			$html_options .= '<option value="'.$k.'"'.($v == $this->value ? ' selected' : '').'>'.$v.'</option>';
		}
		$this->options = $html_options;		
		
		return $this;
	}
}
<?php
namespace samson\cms\web\material;

use samson\core\iModuleViewable;

/**
 * Form tab class
 * 
 * @author Kotenko Nikita <nick.w2r@gmail.com>
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class FormTab implements iModuleViewable
{
	/** Meta static variable to disable default form rendering */
	public static $AUTO_RENDER = true;
	
	/** Tab name for showing in header */
	public $name = 'Form tab';
	
	/** Tab sorting index for header sorting */
	public $index = 0;
	
	/** 
	 * Pointer to parent form object 
	 * @var \samson\cms\web\material\Form 
	 */
	protected $form;
	
	/** Pointer to parent tab */
	protected $parent;	
	
	/** Tab HTML identifier, to connect header, content and control */
	protected $id = '';
	
	/** Collection of child tabs */
	protected $tabs = array();
	
	/** Inner control HTML */
	protected $control_html = '';

	/** Header view path */
	private $header_view = 'form/tab/header';
	
	/** Inner header HTML */
	protected $header_html = '';
	
	/** Content view path */
	private $content_view = 'form/tab/content';
	
	/** Inner content HTML */
	protected $content_html = '';	
	
	/** Control view path */
	private $control_view = 'form/tab/control';
	
	/**
	 * Constructor	 
	 * 
	 * @param Form 		$form	Pointer to parent Form
	 * @param FormTab 	$parent Pointer to parent FormTab
	 */
	public function __construct( Form & $form, FormTab & $parent = null )
	{
		// Save pointer to Form
		$this->form = & $form;	
		
		// Save pointer to parent FormTab
		$this->parent = & $parent;

		// Generate identifier if it not passed
		$this->id = !isset($this->id{0}) ? utf8_translit($this->name).'_tab' : $this->id;
		
		// Add this tab as child
		$this->tabs[] = $this;
	}
	
	/** @see \samson\core\iModuleViewable::toView() */
	public function toView( $prefix = null, array $restricted = array() )
	{	
		// Prepare tab fields as array
		$result = array();
		foreach ( get_object_vars($this) as $k => $v) $result[ $prefix.$k ] = $v;		
		return $result;
	}
	
	/** 
	 * Render HTML tab header part
	 * @return string HTML tab header part 
	 */
	public function header()
	{
		// If we have tab content
		if( isset($this->content_html{0}))
		{
			// Tab sub-headers html
			$sub_headers = '';
		
			// Iterate tab group tabs only if there is atleast two sub headers
			if( sizeof( $this->tabs) > 2 )
			{
				foreach ( $this->tabs as $tab )
				{
					// Don't render this tab as sub header
					if( $tab == $this ) continue;
					
					// Render tab header as sub-header 
					$sub_headers .= m()->view( $this->header_view )->tab( $tab )->output();
				}			
			}
			
			// Render just last tab
			$this->header_html .= m()
				->view( $this->header_view )
				->sub_headers($sub_headers)
				->class(isset($sub_headers{0})?'sub-tabs-list':'')
				->tab( $this ) // Pass this tab object as main
				->tab_id( end($this->tabs)->id ) // Pass last tabs object id as identifier
			->output();
		}
		
		return $this->header_html;
	}
	
	/**
	 * Render HTML tab content part
	 * The main logic is that all nested classes must always call return parent::content()
	 * at the end and must only fill the content_html class variable to properly render a tab
	 * content part. This method passed this current FormTab object to render the top view
	 *  
	 * @return string HTML tab content part
	 */
	public function content()
	{ 		
		$content = '';
		
		// Iterate tab group tabs
		foreach ( $this->tabs as $tab ) 
		{
			// If tab inner html is not empty 
			if( isset($tab->content_html{0})) 
			{			
				// Render top tab content view
				$content .= m()->view( $this->content_view )->tab( $tab )->output();				
			}
		}
				
		// Save content view and return it
		return $this->content_html = $content;
	}
	
	/**
	 * Render HTML tab colntrols part
	 * @return string HTML tab buttons part
	 */
	public function control()
	{ 
		// If we have tab content
		//if( isset($tab->content_html{0}))
		{
			// Iterate tab group tabs
			foreach ( $this->tabs as $tab ) $this->control_html .= m()->view( $this->control_view )->tab( $tab )->output();
		}
		return $this->control_html; 
	}	
}
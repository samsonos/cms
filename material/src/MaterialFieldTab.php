<?php

namespace samson\cms\web\material;

use samson\cms\CMSMaterialField;
use samson\cms\input\Field;
use samson\core\SamsonLocale;

/** 
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class MaterialFieldTab extends FormTab
{
	/** Meta static variable to disable default form rendering */
	public static $AUTO_RENDER = false;
	
	/** Tab sorting index for header sorting */
	public $index = 1;
	
	/** Tab content view path */
	private $content_view = 'form/tab/content/materialfield';
	
	/**
	 * CMS Field object
	 * @var \samson\cms\input\Wysiwyg
	 * @see \samson\cms\input\Wysiwyg
	 */
	protected $cmsfield;
	
	/**
	 * CMSMaterialField DB object pointer
	 * @var CMSMaterialField
	 * @see \samson\cms\CMSMaterialField
	 */
	protected $db_mf;

    /**
     * Constructor
     * @param Form $form
     * @param FormTab $parent
     * @param CMSMaterialField $db_mf
     * @param string $locale
     * @param string $field_type
     */
	public function __construct( Form & $form, FormTab & $parent, CMSMaterialField & $db_mf, $locale = null, $field_type = 'WYSIWYG' )
	{	
		// Create CMS Field object from CMSMaterialField object
		$this->cmsfield = Field::fromObject( $db_mf, 'Value', $field_type );
		
		// Save tab header name as locale name
		$this->name = $locale;

        // Generate unique html identifier
        $this->id = utf8_translit( $parent->name ).'_'.$this->name.'_tab';

		// Save pointers to database CMSMaterialField object 
		$this->db_mf = & $db_mf;
		
		// Call parent
		parent::__construct( $form, $parent );
		
		// Render CMS Field
		$this->content_html = m()->view( $this->content_view )
			->cmsfield( $this->cmsfield )
		->output();		
	}	
}
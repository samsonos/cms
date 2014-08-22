<?php
namespace samson\cms\web\material;

use \samson\core\SamsonLocale;

/**
 * Localized material field tab
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class FieldTab extends FormTab
{
	/** Meta static variable to disable default form rendering */
	public static $AUTO_RENDER = false;	

	/** Tab content view path */
	private $content_view = 'form/tab/content/fields';
		
	/**
	 * CMSMaterial fields table
	 * @var FormFieldTable
	 */
	protected $field_table;
	
	/** Tab sorting index for header sorting */
	public $index = 2;	
	
	/**
	 * Constructor 
	 * @param Form $form Pointer to form
	 */
	public function __construct( Form & $form, FormTab & $parent = null, $locale = null )
	{
		// Call parent constructor
		parent::__construct( $form, $parent );	
		
		// Save tab header name as locale name
		$this->name = $locale == '' ? t('все', true) : $locale;
		
		// Add locale to identifier
		$this->id = $parent->id.($locale == '' ? '' : '-'.$locale);

		// Set pointer to CMSMaterial 
		$material = & $form->material;		
		
		// If material has no related CMSNavs  - Disable tab rendering
		if ( ! isset( $material->onetomany['_structure'] )) $this->render = false;
		else 
		{
			// Create FormFieldTable instance
			$this->field_table = new FormFieldTable( $form->material, $form, $locale );	
			
			// Render tab content
			$this->content_html = $this->field_table->render();

			// If field table is empty - clear output, we don't need empty tables
			if($this->field_table->last_render_count == 0) {
                $this->content_html = '';
            }
		}		
	}

    /**
     * Check if tab has content
     * @return bool True if tab has rendered content
     */
    public function filled()
    {
        return strlen($this->content_html) > 0;
    }
}
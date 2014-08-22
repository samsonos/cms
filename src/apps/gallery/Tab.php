<?php
namespace samson\cms\web\gallery;

use samson\cms\CMSNav;
use samson\activerecord\dbRecord;
use samson\cms\web\material\FormTab;

/**
 * Gallery Tab for CMSMaterial form 
 *
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class Tab extends FormTab
{	
	/** Tab name for showing in header */
	public $name = 'Галлерея';
	
	/** HTML identifier */
	public $id = 'gallery-tab';
	
	/** Tab sorting index */
	public $index = 4;

	/** Content view path */
	private $content_view = 'tumbs/index';

	/** @see \samson\cms\web\material\FormTab::content() */
	public function content()
	{
		// Render content into inner content html
		if( isset($this->form->material) ) $this->content_html = m('gallery')->html_list( $this->form->material->id );	

		// Render parent tab view
		return parent::content();
	}
}
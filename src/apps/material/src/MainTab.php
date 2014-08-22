<?php

namespace samson\cms\web\material;

use samson\cms\CMSNav;
use samson\activerecord\dbRecord;
/**
 * Main CMSMaterial form tab
 * 
 * @author Kotenko Nikita <nick.w2r@gmail.com>
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class MainTab extends FormTab
{
	/** Tab name for showing in header */
	public $name = 'Основные';
	
	/** Content view path */
	private $content_view = 'form/tab/content/main';
	
	/** @see \samson\cms\web\material\FormTab::content() */
	public function content()
	{		
		// Iterate all loaded CMSNavs
		$parent_select = '';
		foreach ( dbQuery('samson\cms\cmsnav')->exec() as $db_structure )
		{			
			// If material is related to current CMSNav
			$selected = '';
			if(isset( $this->form->navs[ $db_structure->id ])) $selected = 'selected';
				
			// Generate CMSNav option
			$parent_select .= '<option '.$selected.' value="'.$db_structure->id.'">'.$db_structure->Name.'</option>';
		}	
		
		// Get user object
		$user = isset($this->form->material->onetoone['_user']) ? $this->form->material->onetoone['_user']  : auth()->user;
		
		// Render content into inner content html 
		$this->content_html = m()->view( $this->content_view )
			->material( $this->form->material )
			->user( $user )
			->parent_select( $parent_select )
		->output();	
				
		// Render parent tab view
		return parent::content();
	}
}
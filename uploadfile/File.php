<?php
namespace samson\cms\input;
use samson\upload\Upload;

/**
 * Generic SamsonCMS input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 *
 */
class File extends Field 
{
	/** Upload file controller */
	public function __upload()
	{			
		s()->async(true);
		
		// Create object for uploading file to server
		$upload = new Upload();
		
		// Uploading file to server;
		$upload->upload($file_path);

		// Save path to file in DB
		Field::fromMetadata( $_GET['e'], $_GET['f'], $_GET['i'] )->save( '/cms/'.$file_path );
	
		// Return upload object for further usage
		return $upload;
	}
	
	/** Delete file controller */
	public function __async_delete()
	{
		s()->async(true);
		
		// Delete path to file from DB
		$field = Field::fromMetadata( $_GET['e'], $_GET['f'], $_GET['i'] );
		
		// Build uploaded file path
		$file = getcwd().Upload::UPLOAD_PATH.basename($field->obj->Value);
		
		// If uploaded file exists - delete it
		if( file_exists( $file ) ) unlink( $file );
	
		// Save empty field value
		$field->save( '' );
		
		return array('status'=>true);
	}
	
	/** @see \samson\core\iModuleViewable::toView() */
	public function toView( $prefix = NULL, array $restricted = array() )
	{	
		// Generate controller links
		$this->set('upload_controller', $this->id.'/upload?f='.$this->param.'&e='.$this->entity.'&i='.$this->obj->id )
		->set('delete_controller', $this->id.'/delete?f='.$this->param.'&e='.$this->entity.'&i='.$this->obj->id );		
		
		//$this->set('empty_text', 'Выберите текст');
		// Call parent rendering routine
		return parent::toView( $prefix, $restricted );
	}
}
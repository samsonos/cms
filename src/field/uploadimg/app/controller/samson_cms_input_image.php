<?php
/** Uplaod file controller */
function samson_cms_input_file_upload()
{	
	s()->async(true);
	
	// Create object for uploading file to server
	$upload = new samson\upload\Upload();
	
	// Uploading file to server;
	$upload->upload();	

	// Save path to file in DB
	samson\cms\input\Field::fromMetadata( $_GET['e'], $_GET['f'], $_GET['i'] )->save( 'cms'.$upload->file_path );
	
	// Return upload object for further usage
	return $upload;
}

/** Delete file controller */
function samson_cms_input_file_delete()
{
	s()->async(true);
	
	// Delete path to file from DB
	$field = samson\cms\input\Field::fromMetadata( $_GET['e'], $_GET['f'], $_GET['i'] );
	
	// Build uploaded file path
	$file = getcwd().samson\upload\Upload::UPLOAD_PATH.basename($field->obj->Value);
	
	// If uploaded file exists - delete it
	if( file_exists( $file ) ) unlink( $file );

	// Save empty field value
	$field->save( '' );
}
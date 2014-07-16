<?php
namespace samson\cms\web\gallery;

/**
 * SamsonCMS application for interacting with material gallery
 * @author egorov@samsonos.com
 */
class App extends \samson\cms\App
{
	/** Application name */
	public $app_name = 'Галлерея';
	
	/** Hide application access from main menu */
	public $hide = true;
	
	/** Identifier */
	protected $id = 'gallery';
	
	/** Relations */
	protected $requirements = array
	(
		'ActiveRecord'
	);	
	
	/** @see \samson\core\ExternalModule::init() */
	public function prepare( array $params = null )
	{
		// Create new gallery tab object to load it 
		class_exists( ns_classname('Tab','samson\cms\web\gallery') );
	}
	
	/**
	 * Controller for deleting material image from gallery 
	 * @param string $id Gallery Image identifier
	 * @return array Async response array
	 */
	public function __async_delete( $id )
	{
		// Async response
		$result = array( 'status' => false );
		
		// Find gallery record in DB
		if( dbQuery('gallery')->id( $id )->first( $db_image ))
		{
			if($db_image->Path != '')
			{
				$upload_dir = \samson\upload\Upload::UPLOAD_PATH;
				// Physycally remove file from server
				if( file_exists( $db_image->Path )) unlink( $upload_dir.$db_image->Path );	
	
				// Delete thumnails
				if(class_exists('\samson\upload\Scale')) foreach (m('scale')->thumnails_sizes as $folder=>$params)
				{
					$folder_path = $upload_dir.$folder;
					if( file_exists( $folder_path.'/'.$db_image->Path )) unlink( $folder_path.'/'.$db_image->Path );
				}	
			}
			
			// Remove record from DB
			$db_image->delete();				

			$result['status'] = true;
		}
		
		return $result;
	}
	
	/**
	 * Controller for rendering gallery images list
	 * @param string $material_id Material identifier 
	 * @return array Async response array
	 */
	public function __async_update( $material_id )
	{				
		return array('status' => true, 'html' => $this->html_list($material_id));
	}
	
	/**
	 * Controller for image upload
	 * @param string $material_id Material identifier 
	 * @return array Async response array
	 */
	public function __async_upload( $material_id )
	{
		// Async responce
		s()->async(true);
		
		// Create object for uploading file to server
		$upload = new \samson\upload\Upload();
		
		$result = array( 'status' => false );
		
		// Uploading file to server
		if( $upload->upload( $fpath, $uname, $fname ) )
		{
			// Check if participant has not uploaded remix yet
			if( dbQuery('material')->MaterialID($material_id)->Active(1)->first($db_material))
			{							
				// Create empty db record
				$photo = new \samson\activerecord\gallery(false);
				$photo->Name = $uname;
				$photo->Src = url()->base().$fpath;
				$photo->Path = $fname;
				$photo->MaterialID = $db_material->id;
				$photo->save();				
				
				// Create thumnails
				if(class_exists('\samson\upload\Scale')) m('scale')->resize($fpath, $fname);

				$result['status'] = true;			
			}
		}
		
		return $result;
	}
	
	/**
	 * Render gallery images list
	 * @param string $material_id Material identifier
	 */
	public function html_list( $material_id )
	{
		// Get all material images
		$items_html = '';
		if( dbQuery('gallery')->MaterialID( $material_id )->order_by('PhotoID')->exec( $images ))foreach ( $images as $image )
		{
            // Get old-way image path
            $path = str_replace(__SAMSON_BASE__, '', $image->Src);
            // Check it
            if (!file_exists($path)) {
                // Use new way
                $path = $image->Path.$image->Src;
            }

            // Render gallery image tumb
			$items_html .= $this->view( 'tumbs/item')
			    ->image($image)
                ->imgpath($path)
			    ->material_id($material_id)
			->output();
		}
	
		// Render content into inner content html
		return $this->view( 'tumbs/index' )
		    ->images( $items_html )
		    ->material_id($material_id)
		->output();
	}
}
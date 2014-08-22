/** JS SamsonCMS Select field interaction */
s('.__inputfield.__file_upload').pageInit( function( fields )
{
	/** Delete file handler */
	s('.__delete', fields ).click( function( btn )
	{
		// Flag for preventing bubbling delete event
		btn.deleting = false;
		
		// If we are not deleting right now - ask confirmation
		if( !btn.deleting && confirm('Удалить файл?'))
		{
			// Get input field block
			var parent = btn.parent('.__inputfield');
			
			// Flag for disabling delete event
			btn.deleting = true;
			
			// Create loader
			var loader = new Loader( parent.parent() );	
			loader.show();		
			
			// Perform ajax file delete
			s.ajax( btn.a('href'), function( responce )
			{	
				// Upload field is became empty
				parent.addClass('empty');
				
				// Remove loader 
				loader.remove();
				
				// Enable delete button for future
				btn.deleting = false;
				
				// Clear upload file value
				s('.__input', parent).val('');
			});
		}
		
	},true, true );
	
	// File selected event
	s('input[type="file"]', fields ).change( function(input)
	{
		// Get file object for uploading
		var file = input.DOMElement.files[0];
		
		// Get parent block
		var p = input.parent();	
		
		// Get DOM elements
		var progress = s('.__progress_bar', p );
		var filename = s('.__file_name', p );			
		var line = s( 'p', progress);	

		// Loading status
		p.addClass('loading');
		
		// Loaded progress
		var loaded_percent = 0;			
		
		// Create async upload request
		var xhr = new XMLHttpRequest();
	    var uploadStatus = xhr.upload;
		    
	    /** Output upload status */
	    var showStatus = function( text )
	    {		    	
	    	filename.html( text );
	    	
	    	p.removeClass('loading');	    	
	    };

	    // Upload progress handler
	    uploadStatus.addEventListener("progress", function (ev)
	    {
	    	// Calculate loaded part
        	var c = ev.lengthComputable ? Math.ceil(ev.loaded / ev.total) * 100 : 0;       	    	
        	
        	// If file not yet loaded and this is not "old" progress event
            if ( c > loaded_percent ) line.width( (loaded_percent = c)+'%');
            
	    }, false);

	    // Upload error handler
	    uploadStatus.addEventListener("error", function (ev) 
	    {	    	
	    	showStatus('Ошибка загрузки файла');
	    	
	    }, false);
	    
	    // Upload success handler
	    uploadStatus.addEventListener("load", function (ev) 
	    {	    	
	    	showStatus( file.name );
	    	
	    	// Upload field is not empty anymore
	    	p.removeClass('empty');
	    	
	    }, false);
	    
	    // Get upload controller url
	    var url = s('.__action', p ).val();

	    // Perform request
	    xhr.open( "POST", url, true );
        xhr.setRequestHeader("Cache-Control", "no-cache");
        xhr.setRequestHeader("Content-Type", "multipart/form-data");
        xhr.setRequestHeader("X-File-Name", file.name );
        xhr.setRequestHeader("X-File-Size", file.size );
        xhr.setRequestHeader("X-File-Type", file.type );
        //xhr.setRequestHeader("Content-Type", "application/octet-stream");
        xhr.send( file );
	});
});
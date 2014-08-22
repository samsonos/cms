var isImagesLoaded = function( selector, handler )
{
	// Get all gallery images
	var images = s( selector );
	
	// Collection of loaded images
	var loaded = [];
	
	// Collect data about loaded pictures
	images.each(function(img)
	{
		if( img.loaded() )	loaded.push(img);
	});
	
	// If we have loaded all available pictures
	if( loaded.length >= images.length )
	{
		if(handler != undefined ) return handler();
	}
	
	// Bind image loaded event
	images.load(function(img)
	{
		// If image has been loaded - add it to loaded collection
		if( img.loaded() )	loaded.push(img);
		
		// If we have loaded all available pictures
		if( loaded.length >= images.length )
		{
			if(handler != undefined ) return handler();
		}
	});	
	
	// Save return
	setTimeout(function()
	{
		return handler();
	}, 2000 );
};
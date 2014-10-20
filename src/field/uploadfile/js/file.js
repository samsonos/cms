/** JS SamsonCMS Select field interaction */
s('.__inputfield.__file_upload').pageInit( function( fields )
{
	/** Delete file handler */
	s('.__deletefield', fields ).click( function( btn )
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
				s('.__input', parent).show();
				s('.__delete', parent).hide();
			});
		}

	},true, true );

    // File selected event
    uploadFileHandler( s('input[type="file"]', fields ) );
});
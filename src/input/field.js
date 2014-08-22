/**
 * Simple textarea editable input field
 * @param fields Collection of input fields to initialize
 * @param saveHandler External pointer to field saving
 * @constructor
 */
var SamsonCMS_InputField = function( fields, saveHandler )
{
	// Pointer
	var o = this;

    /**
     * Click on input field handler and transform it to editable textarea
     * @param field SamsonJS object
     */
    o.clickToEdit = function( field )
    {
        // Check if field is already opened
        if( field.opened == undefined || field.opened == false )
        {
            // Set flag that field is opened
            field.opened = true;

            // Parent element
            var p = field.parent();

            // Get parent offset
            var of = p.offset();

            // Get field input object
            var tb = s('.__input',field);

            // No CSS for "file" input
            if( tb.a('type') != 'file')
            {
                // Position and sizing
                tb.css('position','fixed');
                tb.left( of.left + 1);
                tb.top( of.top + 1 );
                tb.css( 'width',p.width()+'px');
                tb.css( 'height', p.height()+'px');
                tb.css( 'z-index', 99);
                tb.css( 'line-height', p.height()+'px');
                tb.css( 'padding-left', '5px');
                tb.show();
                tb.focus();

                // Fix to put pointer at the end
                var value = tb.val();
                tb.val('');
                tb.val(value);
            }
        }
    };

    /**
     * Field focus loose handler
     */
    o.blur = function( field )
    {

    };

	/** Init fields handler */
	this.init = function( fields, saveHandler )
	{
		/** Init */
		fields.each(function( field )
		{
			// Create ajax loader object
			var loader = new Loader( field.parent() );

			// Current value view
			var sp = s( 'span', field );

			// Original value
			var original = s( '.__hidden', field );

			// Get localized empty text
			var empty_text = s('input[name="__empty_text"]',field).val();

            // Bind click event
            field.click( o.clickToEdit, false, true );

			// Save handler
			s('.__input', field ).blur( function(tb)
			{
				// Get current tb value
				var new_value = tb.val();

                // Eneble field editing flag
                field.opened = false;

				// If value changed
				if( new_value !== original.val() )
				{
					// Hide field view
					sp.hide();

					// Show loader
					loader.show();

					// Create form for async post
					var form = s('<form method="post" enctype="multipart/form-data"></form>');

					// Set field action as form action
					form.a('action', s('input[name="__action"]', field).val());

					// Add all field hidden fields to form
					s('input[type="hidden"]', field).each(function(hidden)
					{
						form.append('<textarea name="'+hidden.a('name')+'">'+hidden.val()+'</textarea>');
					});

					// Add new field value
					form.append('<textarea name="__value">'+new_value+'</textarea>');

					// Perform ajax save
					form.ajaxForm(function(responce)
					{
						// Hide loader
						loader.hide();

						// If external save handler is passed - call it
						if( saveHandler ) saveHandler( responce, field, sp );
						// Default behavior
						else
						{
							// Think it is not empty
							field.removeClass('__empty');

							// Set field view
							sp.html( new_value );

							// Fill sp with correct new value
							if( !new_value.length  )
							{
								field.addClass('__empty');
								sp.html( empty_text );
							}
						}

						// Save new value as original value
						original.val( new_value );

						// Show value view
						sp.show();
					});
				}
				else sp.show();

				// Destroy input
				tb.hide();
			});
		});


	};

	// Perform object initialization
	o.init( fields, saveHandler );
};


/** JS SamsonCMS Input field interaction on DOM ready */
s('.__inputfield.__textarea').pageInit( SamsonCMS_InputField );
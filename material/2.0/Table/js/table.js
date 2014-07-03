/**
 * Materials table
 * @param table DOM table element
 */
function SamsonCMSTable ( table )
{
	/** Event: Publish/unpublish material */
	function publish( obj )
	{		
		// Спросим подтверждение 
		if (confirm(obj.a('title'))) {
			// Perform ajax request and update JS on success
			s.ajax( s( 'a.publish_href', obj.parent()).a('href'), init );			
		}
	};
	
	/** Event: Remove material */
	function remove( obj ){	if( confirm( obj.a('title') ) ) s.ajax( obj.a('href'), init );	};
	
	/** Event: Copy material */
	function copy( obj ){ if( confirm( obj.a('title') ) ) s.ajax( obj.a('href'), init ); };
	
	/**
	 * Обновить таблицу материалов
	 * 
	 * @param data Содержание таблицы для обновления
	 */
	function init( serverResponse )
	{		
		// If we have responce from server
		if( serverResponse ) try
		{
			// Parse JSON responce
			serverResponse = JSON.parse( serverResponse );
					
			// If we have table html - update it
			if( serverResponse.table_html ) table.html( serverResponse.table_html );			
			if( serverResponse.pager_html ) s('.table-pager').html( serverResponse.pager );
		}		
		catch(e){ s.trace('Ошибка обработки ответа полученного от сервера, повторите попытку отправки данных'); };		
		
		// If we have successful event response or no response at all(first init)
		if( !serverResponse || (serverResponse && serverResponse.status) )
		{					
			// Add fixed header to materials table
			s('.material-table').fixedHeader();

			// Bind publish event
			s( 'input#published' ).click( publish, true, true );
			
			// Bind remove event
			s( 'a.delete' ).click( remove, true, true );				
		}
	};

    /**
     * Asynchronous material search
     * @param search Search query
     */
	function material_search(search)
	{
		// Safely get object
		search = s(search);
		
		var cmsnav = 0;//s('#cmsnav_id').val(); 
		var page = 1;
		
		// Ajax request handle
		var request;
		var timeout;
		
		// Key up handler
		search.keyup(function(obj)
		{		
			if( request == undefined )
			{			
				// Reset timeout on key press
				if ( timeout != undefined ) clearTimeout( timeout );
				
				// Set delayed function
				timeout = window.setTimeout(function()
				{			
					// Get search input
					var keywords = obj.val();
					
					if ( keywords.length < 2 ) keywords = '';
					
					// Disable input
					search.DOMElement.enabled = false;

                    // Perform async request to server for rendering table
                    request = asyncSearch(cmsnav, keywords, page, function(response){
                        // re-render table
                        init(response);

                        // Clear request variable
                        request = undefined;
                    });
					
				}, 1000);
			} 
		});
	}

    /**
     * Asynchronous request for table search
     * @param cmsnav    Current selected SamsonCMS navigation identifier
     * @param keywords  Material search keywords
     * @param page      Current search page results
     * @param handler   External handler on ajax success request
     * @returns Asynchronous request handle
     */
    var asyncSearch = function(cmsnav, keywords, page, handler) {
        // Avoid multiple search requests
        if(!searchInitiated) {
            // Set flag
            searchInitiated = true;

            // Create generic loader
            var loader = new Loader(s('#content'));

            // Show loader with i18n text and black bg
            loader.show(s('.loader-text').val(), true);

            // Perform async request to server for rendering table
            return s.ajax( 'material/update/table/'+cmsnav+'/'+keywords+'/'+page, function(response)
            {
                // re-render table
                init(response);

                // Call external handler
                if(handler) {
                    handler();
                }

                loader.hide();

                // Release flag
                searchInitiated = false;
            });
        }
    }

    // Cache search field
    var searchField = s('input#search');

    // Flag to preserve multiple search requests
    var searchInitiated = false;

	// Init table live search
	material_search(searchField);

    // Disable search form submit
    s('form.search').submit(function(){
        // Get search input
        var keywords = searchField.val();

        // Perform async request to server for rendering table
        asyncSearch(0, keywords, 1, function(response){
            // re-render table
            init(response);
        });

        return false;
    });
	
	// Init table
	init();
};

/**
 * Инициализация JS для таблицы материалов
 */
s('#material').pageInit( function( _parent ) 
{		
	// Повесим обобщенный обработчик таблицы
	SamsonCMSTable( s('.material-table') );
});
function StructureTree()
{	
	// Флаг нажатия на кнопку управления
	var ControlFormOpened = false;
	
	// Указатель на текущий набор кнопок управления 
	var ControlElement = null;
	
	/**
	 * Обработчик открытия формы ЭСС
	 */
	var formOpened = function( formContainer )
	{	
		s('#std_material').autocomplete('material/ajax_autocomplete', s('#std_material_id'));		
	};
	
	/**
	 * Обработчик закрытия формы ЭСС
	 */
	var formClosed = function( formContainer ){};
	
	/**
	 * Обработчик результата выполнения действия контроллера
	 */
	var ActionResponceHandler = function ( serverResponse, formSubmited, btnSubmit ){ UDBC.handleResponse( serverResponse, init ); };
	
	/**
	 * Обработчик нажатия на кнопку управления
	 */
	var ControlClickHandler = function(){ ControlFormOpened = true; };
	
	/**
	 * Обработчик сокрытия формы управления
	 */
	var ControlFormHideHandler = function(){
        ControlFormOpened = false;
        s( '.control-buttons', ControlElement ).hide();
    };
	
	/**
	 * Инициализировать дерево ЭСС
	 */
	var init = function( html )
	{				
		// Если передано HTML - заполним дерево
		if( html && html.length ) s( '.tree-container' ).html( html );		
		
		// Обработчик добавления подчиненного ЭСС даному
		/*s( 'a.control.add, a.btn.add').FormContainer({
			showedHandler 	: formOpened,
			hideHandler		: formClosed,
			placeMode 		: 'creatorOver',
			submitHandler 	: ActionResponceHandler,
			hideHandler		: ControlFormHideHandler
		}).click( ControlClickHandler );	*/
		
		// Обработчик добавления подчиненного ЭСС даному
		/*s( 'a.control.edit').FormContainer({
			showedHandler 	: formOpened,
			hideHandler		: formClosed,
			placeMode 		: 'creatorOver',
			submitHandler 	: ActionResponceHandler,
			hideHandler		: ControlFormHideHandler
		}).click( ControlClickHandler );*/
		
		// Обработчик удаления данного ЭСС 
		/*s( 'a.control.delete').FormContainer({
			placeMode 		: 'creatorOver',
			submitHandler 	: ActionResponceHandler,
		});*/
		
		// Обработчик редактирования прав для ЭСС
		s( 'a.control.permissions' ).FormContainer({
			// Инициализируем форму управления правами
			showHandler 	: permissionForm,
			placeMode 		: 'creatorOver',
			hideHandler		: ControlFormHideHandler
		}).click( ControlClickHandler );			
		
		// Обработчик редактирования дополнительных полей для ЭСС
		s( 'a.control.fields' ).FormContainer({	
			showHandler 	: fieldForm,
			placeMode 		: 'creatorOver',
			hideHandler		: ControlFormHideHandler
		}).click( ControlClickHandler );			
	
		/*// Обработчик изменения порядкового номера ЭСС
		UDBC.bindAction( s( 'a.control.move-up' ), this );
		// Обработчик изменения порядкового номера ЭСС
		UDBC.bindAction( s( 'a.control.move-down' ), this );*/
		
		// Обработчик отображения/скрытия кнопок управления ЭСС
		s( '.structure-element' )
		.mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
		.mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
		
		
		// Выведем дерево ЭСС
		s( '.tree-container' ).treeview();
	};	
	
	// Инициализируем дерево ЭСС
	init();	
}

/**
 * Инициализация модуля ЭСС
 */
s('#structure').pageInit( StructureTree );
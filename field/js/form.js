/**
 * Форма редактирования прав для сущности
 */
var fieldForm = function( fieldForm )
{
	// Получим идентификатор сущности
	var structure_id = s( '#StructureID', fieldForm ).val();
	
	/**
	 * Обработчик результата выполнения действия контроллера
	 */
	var ActionResponceHandler = function ( serverResponce, formSubmited, btnSubmit ){ UDBC.handleResponse( serverResponce, init ); };
		
	/**
	 * Инициализщировать форму
	 */
	var init = function( htmlData )
	{		
		// Перезаполним родительскую форму если есть данные
		if( htmlData ) fieldForm.updateForm( fieldForm, htmlData );		
	};
	
	// Обработчик загрузки картинки в галерею		
	s('#btnAddField', fieldForm ).FormContainer({
		filler 			: 'field/ajax_form/' + structure_id,
		placeMode 		: 'creatorOver',
		submitHandler 	: ActionResponceHandler
	});
	
	// Обработчик загрузки картинки в галерею		
	s('#btnCloneField', fieldForm ).FormContainer({
		filler 			: 'field/ajax_clone/' + structure_id,
		placeMode 		: 'creatorOver',
		submitHandler 	: ActionResponceHandler
	});
	
	// Обработчик загрузки картинки в галерею		
	s('a.edit-field-button', fieldForm ).FormContainer({		
		placeMode 		: 'creatorOver',
		submitHandler 	: ActionResponceHandler
	});	
	
	// Обработчик удаления дополнительного поля
	UDBC.bindAction( s( 'a.delete-field-button', fieldForm ), init );	
	// Обработчик удаления дополнительного поля
	UDBC.bindAction( s( 'a.clone-field-button', fieldForm ), init );
};

// Инициализация формы управления правами сущности
s('form.field-list').pageInit( fieldForm );
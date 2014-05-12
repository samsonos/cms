/**
 * Форма редактирования прав для сущности
 */
var permissionForm = function( permissionsForm )
{
	// Получим идентификатор сущности
	var o_id = s( '#ObjectID', permissionsForm ).val();
	// Получим тип сущности
	var o_type = s( '#ObjectType', permissionsForm ).val();
	
	/**
	 * Обработчик ответа от сервера
	 * 
	 * @param serverResponce Ответ полученный от сервера
	 */
	var responceHandler = function( serverResponce, successHandler )
	{
		// Преобразуем ответ от сервера в объект
		try{ serverResponce = JSON.parse( serverResponce );	} catch(e){};

		// Если ответ от сервера разпознан 
		if( serverResponce && ! serverResponce.error )
		{	
			// Если были ошибки - выведем её
			if( serverResponce.error ) alert( serverResponce.error );
			// Иначе обновим таблицу полученными данными 
			else if( serverResponce.data && serverResponce.data.length ) init(serverResponce.data);			
		}
	};	
	
	/**
	 * Инициализщировать форму
	 */
	var init = function( htmlData )
	{		
		// Перезаполним родительскую форму если есть данные
		if( htmlData ) 
		{
			// Если у формы есть специальный метод для обновления выполним его
			if( permissionsForm.updateForm ) permissionsForm.updateForm( permissionsForm, htmlData );
			// Иначе по старинке =)
			else permissionsForm.html( htmlData );
		}
		
		// Обработчик загрузки картинки в галерею		
		s('#btnAddRule', permissionsForm ).FormContainer({
			filler : 'permission/rule_form/' + o_id + '/' + o_type ,
			placeMode : 'creatorOver',
			submitHandler : responceHandler
		});
		
		// Обработчик загрузки картинки в галерею		
		s('a.btnEditRule', permissionsForm ).FormContainer({		
			placeMode : 'creatorOver',
			submitHandler : responceHandler
		});	
		
		// Обработчик удаления правила
		s( 'a.btnDeleteRule', permissionsForm ).click(function(link)
		{
			s.ajax( link.a('href'), responceHandler );
		}, true, true );
	};
	
	// Инициализируем форму
	init();	
};

// Инициализация формы управления правами сущности
s('form.permissions').pageInit(function(form)
{
	// Инициализируем форму управления правами
	permissionForm( form );
});
/**
 * Функция-объект для обработки всех действий модуля пользователей
 *  
 * @param table Указатель на таблицу пользователей
 */
function commentTable( tableSelector )
{
	// Указатель на таблицу пользователей
	var table;	
	
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
			else if( serverResponce.data && serverResponce.data.length ) init( serverResponce.data );
		}
	};	
	
	/**
	 * Обработчик удаления пользователя 
	 */
	var deleteHandler = function( link )
	{		
		// Выполним ассинхронный запрос на удаление пользователя 
		if( confirm('Удалить комментарий?') )		
			s.ajax( link.a('href'), responceHandler );
	};	
	
	function moderate( obj )
	{		
		// Спросим подтверждение 
		return confirm( ( obj.a('checked') ) ? 'Опубликовать?' : 'Снять с публикации?'  );			
	};
	
	/**
	 * Инициализировать таблицу пользователей
	 */
	var init = function( htmlData )
	{
		// Если переданы данные таблицы то обновим её содержимое
		if( htmlData ) s( tableSelector ).html( htmlData );
		
		// Получим таблицу пользователей
		table = s( tableSelector );
		
		// Обработчик удаления пользователя
		s('a.btnDeleteComment', table ).click(	deleteHandler, true, true );			
		
		// Обработчик открытия формы редактирования пользователя
		s('.btnEditComment', table ).FormContainer({			
			placeMode : 'creatorOver',
			submitHandler : responceHandler
		});
		
		UDBC.bindClick( s( 'input#moderate' ), init, null, moderate, function(obj){
			return s( 'a.publish_href', obj.parent()).a('href');					
		});
	};	
	
	// Инициализируем таблицу
	init();	
};

// Инициализация модуля JS пользователей 
s('#comment').pageInit(function(commentPage)
{	
	// Выполним обработчик действий модуля
	commentTable( 'table' );		
});
/**
 * Функция-объект для обработки всех действий модуля пользователей
 *  
 * @param table Указатель на таблицу пользователей
 */
function userTable( tableSelector )
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
		if( confirm('Удалить пользователя ' + s('td.fio a', link.parent().parent()).text() + '?') )		
			s.ajax( link.a('href'), responceHandler );
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
		s('a.btnDeleteUser', table ).click(	deleteHandler, true, true );			
		
		// Обработчик открытия формы редактирования пользователя
		s('td.fio a, .btnEditUser', table ).FormContainer({			
			placeMode : 'creatorOver',
			submitHandler : responceHandler
		});			
		
		// Повесим обработчик создания пользователя на кнопку из меню
		s('#btnCreateUser').FormContainer({			
			placeMode : 'creatorOver',
			submitHandler : responceHandler
		});	
	};	
	
	// Инициализируем таблицу
	init();	
};

// Инициализация модуля JS пользователей 
s('#user').pageInit(function(userPage)
{	
	// Выполним обработчик действий модуля
	userTable( 'table' );		
});
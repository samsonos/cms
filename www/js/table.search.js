/**
 * Выполнить обработчик поиска по содержимому
 * @param input_selector	Селектор или объект поля в которое вводится поисковый запрос
 * @param data_selector		Селектор данных по которым выполняется поиск
 * @param complete_handler	Обработчик  результата
 */
function table_search( input_selector, data_selector, complete_handler )
{	
	// Безопасно получим поле ввода
	input_selector = s( input_selector );
	
	// Получим элементы для фильтрации
	var rows = s( data_selector );
	
	// Количество строк в таблице
	var size = rows.length;
	
	// Определим необходимо ли выводить поисковое окно
	if( rows.length > 0 ) input_selector.parent().show();		
	
	// Обработчик отпускания клавиши в поле ввода
	input_selector.keyup( function(input)
	{			
		// Значение фильтра
		var value = input.val();
		
		// Фильтр пустой - отобразить все строки
		if( value.length < 1 ) rows.show();
		else
		{
			// Выражение для поиска
			var filter_text	= new RegExp( value, "i");
			
			// Итератор для прохода 
			var iterator = size;
			
			// Выполняем пока не дойдем до нуля
			while( iterator-- )
			{				
				// Получим строку таблицы
				var tr = rows.elements[ iterator ];
							
				// Получим имя отеля
				var text  = tr.text();
			
				// Если первые символы совпадают то показать иначе скрыть, делаем это только для списка отелей
				if( ! filter_text.test( text ) ) tr.DOMElement.style.display = 'none';				
				else tr.DOMElement.style.display = 'table-row';
			}			
		}		
		
		// Если ничего отображать то вызовем обработчик
		if( complete_handler ) complete_handler();		
	});
}
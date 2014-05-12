/**
 * Обраболчик настройки работы фрейма отображения
 * @param _view Указатель на фрейм для настройки
 */
function initView( _view )
{
	// Получим "тело" фрейма
	var frameBody = s('body', _view );
	
	// Создадим ссылку на CSS ресурс для отрисовки элементов CMS во фрейме представления
	frameBody.append('<link type="text/css" rel="stylesheet" href="/cms/src/?r=/css/show.css&a=local&m=*">');
	
	// Повесим обработчик нажатия на редактирование материала
	s( 'a.__cms__link__', frameBody ).click(function( link )
	{
		// Перейдем к редактированию элемента материала
		window.location.href = link.a('href');
	}, true );
	// Обработаем ОСТАЛЬНЫЕ ссылки
	s('a', frameBody ).each(function(link)
	{
		// Преобразуем только ссылки самого сайта, и те которые еще не преобразованны
		if( ! link.hasClass('__cms__link__') && !link.a('href').match(/cms_editor/gi) )	
		{			
			link.a('href',link.a('href') + '/?cms_editor=1');
		}
	});	
	
	// Обработчик изменения ссылки внутри фрейма
	_view.load( initView );
}

// Инициализируем JS для молуля SamsonCMS:Show
s('#show').pageInit(function(element)
{
	// Получим фрейм в котором необходимо отобразить сайт
	initView( s( 'iframe', element ) );
});
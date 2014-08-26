/**
 * SamsonJS Tabs plugin
 * 
 * Расширение функционала JavaScript фреймоворка SamsonJS
 * для вывода закладок в контейнере
 * 
 */
var SamsonJSTabs = 
{	
	tabs : function( tabSwitchHandler )
	{
		// Контейнер с закладками
		var container = this;
		
		// Коллекция закладок
		tabArray = [];	
		
		// Коллекция загловков закладок
		tabHeaders = [];	
		
		// Указатель на текущую активную закладку
		activeTab = null;	
		
		/**
		 * Переключится на конкретную закладку
		 * 
		 * @param active_tab Указатель на закладку на которую необходимо переключиться
		 */
		showTab = function( active_tab )
		{				
			// Если закладки вообще есть
			if( tabArray && tabArray.length )
			{	
				// Если ниче не передано то выведем первую закладку
				if( ! active_tab || !active_tab.length ) active_tab = tabArray[ 0 ];
				
				// Получим порядковый номер закладки
				var index = getTabIndex( active_tab );		
						
				// Скроем все кроме указанной
				for ( var i = 0; i < tabArray.length; i++) 
				{					
					// Над указанной закладкой ни каких действий не выполняем
					if( i != index )
					{
						// Скроем закладку
						tabArray[ i ].hide(); 
						
						// Уберем класс с заголовка закладки
						tabHeaders[ i ].removeClass( 'active' );		
					}
				}					
				
				// Установим стиль для заголовка закладки
				getHeaderByTab( active_tab ).addClass('active');
				
				// А необходимую отобразим
				active_tab.show();
				
				// Попытаемся установить в хеш URL идентификатор закладки
				try{window.location.hash = active_tab.a('id');}catch(e){}
				
				// Установим указатель на активную закладку
				container.activeTab = active_tab;
			}
		};
		
		/**
		 * Получить порядковый номер закладки
		 * 
		 * @param tab Указатель на закладку
		 * @return Порядковый номер закладки
		 */
		getTabIndex = function( tab )
		{
			// Если передана закладка
			if( tab && tab.length && tabArray )
			{
				// Переберем все закладки
				for ( var i = 0; i < tabArray.length; i++) 
				{
					// Если идентификатор закладки соответствуем переданному 
					// вернем индекс
					if( tab.a('id') == tabArray[ i ].a('id') ) return i;
				}
			}
		};		
		
		/**
		 * Получить заголовок закладки по самой закладке
		 * 
		 * @param tab Указатель на закладку
		 * @return Указатель на заголовок закладки
		 */
		getHeaderByTab = function( tab )
		{
			// Получим порядковый номер закладки
			var index = getTabIndex( tab );				
			
			// Вернем заголовок закладки, если индекс подходит
			if( index < tabHeaders.length ) return tabHeaders[ index ];
		};
		
		/**
		 * Получить закладку по её заголовку
		 * 
		 * @param tabHeader Указатель на заголовок закладки
		 * @return Указатель на закладку
		 */
		getTabByHeader = function( tabHeader )
		{
			// Получим ссылку в заголовке закладки
			tab_link = s( 'div', tabHeader );			
			
			// Получим ссылку на закладку				
			if( tab_link.length ) 
			{					
				// Получим "локальную" ссылку на конте
				var tab_id =  tab_link.a( 'class' );			
				
				// Попытаемся получить сам контейнер закладки 	
				return s( tab_id, container );			
			}			
		};		
		
		/**
		 * Инициализировать закладки
		 */
		init = function()
		{
			// Если есть данные
			if( container.length ) 
			{
				// Получим заголовки закладок
				headers = s( '.tabs-list li', container );					
				
				// Переберем оглавление закладок 
				headers.each( function( tabHeader )
				{						
					// Если это не закладка а просто контейнер - пропустим её
					if( tabHeader.hasClass( 'sub-tabs-list' )) return true;
					
					// Получим закладку по заголовку
					_tab = getTabByHeader( tabHeader );				
						
					// Добавим заголовок закладки
					tabHeaders.push( tabHeader );
					
					// Получим закладку по её заголовку
					// Добавим саму закладку по описанию в её заголовке
					tabArray.push( _tab );					
				})			
				// Повесим на заголовки закладок обработчики их переключения
				.click( function( tabHeader )
				{			
					// Определим текущую закладку
					var tab = getTabByHeader( tabHeader );	
								
					// Если передан обработчик переключения закладок
					if( tabSwitchHandler ) tabSwitchHandler( tab );
					
					// Получим закладку по её заголовку
					// Добавим саму закладку по описанию в её заголовке
					showTab( tab );				
					
				}, true, true );			
			
				
				// Повесим на ссылки в заголовках закладок обработчики их переключения
				s( 'a', headers ).click( function( link )
				{						
					// Определим текущую закладку
					var tab = getTabByHeader( link.parent() );
					
					// Если передан обработчик переключения закладок
					if( tabSwitchHandler ) tabSwitchHandler( tab );
					
					// Получим закладку по её заголовку
					// Добавим саму закладку по описанию в её заголовке
					showTab( tab );			
					
				}, true, true );				
				
				// Если есть хеш в URL то выставим необходимую закладку
				showTab( s( window.location.hash ) );		
				
				// Если передан обработчик переключения закладок - выполним его
				if( tabSwitchHandler ) tabSwitchHandler( this.activeTab );
				
				// Запишем переменные в объект
				container.tabArray = tabArray;
				container.tabHeaders = tabHeaders;
			}
		};		
		
		// Выполним инициализацию
		init();	
		
		// Интерфейс
		this.showTab = showTab;		
		
		// Вернем ссылку на себя
		return this;
	}
};

//Добавим плагин к SamsonJS
SamsonJS.extend( SamsonJSTabs );
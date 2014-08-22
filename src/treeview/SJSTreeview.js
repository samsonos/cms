/**
 * SamsonJS Treeview plugin 
 */
var SamsonJSTreeview = 
{	
	treeview : function()
	{			
		// Указатель на самого себя
		var _self = this;
		
		// Если есть элементы DOM в выборке
		if ( _self.length )
		{			
			// Добавим CSS стиль для дерева
			s( 'ul', _self ).addClass('sjs-treeview');	
			
			// Установим всем веткам дерева специальный класс
			s( 'li', _self ).addClass('notlast');
			
			// Установим специальный класс для последней ветки дерева на 0-м уровне 
			s( 'li:last-child', _self ).removeClass('notlast').addClass('last');
		
			// Переберем все элементы в данном списке
			s('li',_self).each(function(li)
			{				
				// Получим подчиненные ветки для данного элемента списка
				// и если такие имеются то установим специальный стиль
				// указываабщий на то что ветка может "сворачиваться"
				if( s( 'ul', li ).length > 0 ) 
				{	
					li.prepend('<div class="hitarea"></div>');
					li.addClass('collapsable');				
				}
				
				// Пометим специальным классом последнюю ветку
				s( 'li:last-child', li ).addClass('last');
			})
			// Обработчик "сворачивания"/"разворачивания" ветки дерева
			.click( function(li)
			{
				// "Передернем" класс для сокрытия ветки дерева
				li.toggleClass('collapsed');
				
			}, false, true );
			
			// Обработчик "сворачивания"/"разворачивания" ветки дерева
			s('.hitarea').click( function(ha)
			{
				// "Передернем" класс для сокрытия ветки дерева
				ha.parent().toggleClass('collapsed');				
			}, false, true );
		}		
		
		/**
		 * Обработчик принудительного "сворачивания" дерева.
		 * Если нечего не передано - свернем все дерево целеком
		 * 
		 * @param li Указатель на конкретную ветку дерева для сворачивания
		 * @return SamsonJS Указатель на самого себя для цепирования
		 */
		this.collapse = function( li )
		{
			// Указатель на ветки деревье для сворачивания
			var selector = null;
			
			// Если конкретная ветка дерева не указана - "свернем" все ветки дерева
			if( ! li ) selector = s( 'li', _self );
			// Иначе свернем конкретную ветку дерева
			else selector = li; 
			
			// Выполним "сворачивание"
			selector.addClass('collapsed');
		};		
		
		// Вернем указатель на самого себя для цепирования
		return _self;
	}
};

// Добавим плагин к SamsonJS
SamsonJS.extend( SamsonJSTreeview );
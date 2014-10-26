/**
 * SamsonJS Gallery plugin 
 */
var SamsonJSGallery = 
{	
	/**
	 * Выполнить изменение размера картинки
	 * 
	 * @param max Значение максимального размера(Высота|Ширина)
	 */
	resizeToBox : function( boxWidth, boxHeight )
	{
		// Переберем все элементы из текущей выборки и вернем себя же
		return this.each(function(img)
		{			
			// Получим текущие размеры картинки
			var c_width = img.width();
			var c_height = img.height();	
			
			// Выполним уменьшение картинки, только если она реально больше коробки
			if( (c_width > boxWidth) || (c_height > boxHeight) )
			{			
				// Получим соотношение сторон картинки
				var originRatio = c_height / c_width;
				
				// Получим соотношение сторон в коробке
				var boxRatio = boxHeight / boxWidth;
			
				// Сравним соотношение сторон картинки и "целевой" коробки для определения
				// по какой стороне будем уменьшать картинку
				if ( originRatio < boxRatio)
				{
					c_width = boxWidth;
					c_height = c_width * originRatio;
				}
				else
				{
					c_height = boxHeight;
					c_width = c_height / originRatio;
				}		
				
				// Установим новые параметры картинки
				img.width(c_width);
				img.height(c_height);	
			}
		});
	},	
	
	gallery : function( _width, _height )
	{					
		// Установим стандартные параметры работы метода
		_width 	= _width || 200;
		_height = _height || 150;
		
		// Если есть выборка элементов DOM
		if( this.length )
		{
			// Получим все картинки
			var images = s( 'img', this );			
			
			// Если в указанном контейнере вообще есть картинки
			if( images.length )
			{				
				s.trace('SamsonJS Gallery v 1.2 - Loaded ' + images.length + ' images');
				
				// Контейнер в котором размещены элементы для галлереи
				var container = this;
				
				// Номер текущей картинки
				var index = 0;
			
				// Добавим специальный класс
				container.addClass( 'sjs-gallery' );			

				// Переберем все картинки в полученном контейнере
				images.each(function(img)
				{						
					/**
					 * Подготовить изображение
					 */
					var init_image = function( image )
					{		
						// Если картинка не была инициализирована
						if( (image.image_size === undefined) )
						{							
							// Получим "РЕАЛЬНЫ" размер картинки
							image.image_size = { width:image.width(), height:image.height() };	
									
							// Контейнер tumbnail для изображения
							var tn = s('<div class="sjs-gallery-tn"></div>');							
						
							// Уменьшим картинки до размера tumbnail
							image.resizeToBox( _width, _height );
												
							// Выведем картинку
							image.css( 'display', 'block' );
							
							// Добавим tumbnail
							image.parent().prepend( tn );
							
							// Добавим изображение в tumbnail
							tn.append( image );
							
							// Повесим обработчик для закрытия 
							s(document.body).click(function( obj, opt, e )
							{
								// 
								if( ! s(e.toElement).hasClass('sjs-gallery-image') )
								{
									s('table.sjs-gallery-wraper').remove();
								}							
							});		
							
							// Обработчик нажатия на tumbnail для простотра галлереи картинок
							image.click( show_img, true, true );								
						}
					};					
					
					/**
					 * Показать картинку из галереи
					 */
					var show_img = function( image, options )
					{							
						// Удалим все другие контейнеры отображающие галерею
						s('table.sjs-gallery-wraper').remove();
						
						// Если картинка существует и её размеры заданы
						if( image && image.image_size )
						{															
							// Создадим контейнер для её отображения
							var wrapper = s('<table class="sjs-gallery-wraper"><tr><td class="sjs-gallery-prev-cnt"><span class="sjs-gallery-prev sjs-gallery-btn" title="Назад">←</span></td><td class="sjs-gallery-viewport"></td><td class="sjs-gallery-next-cnt"><span class="sjs-gallery-next sjs-gallery-btn" title="Вперед">→</span></td></tr></table>');							
							
							// Добавим контейнер для вывода изображения
							s(document.body).append(wrapper);		
							
							// Рассчитаем вертикальную позицию контейнера с учетом прокрутки
							var wrapperTop = s.scrollTop() + wrapper.top();
								
							// Склонируем картинку из галлереи
							var n_img = image.clone();
							// Снимем все события с картинки
							n_img.unbind( 'click' );
								
							// Укажем клону его индекс
							n_img.index = image.index;
										
							// Установим
							n_img.width(image.image_size.width);
							n_img.height(image.image_size.height);													
						
							// Изменим размер картинки для "заполнения" родительского контейнера
							n_img.resizeToBox( wrapper.width(), wrapper.height() );			
							
							// Добавим клону картинки "красивый" класс
							n_img.addClass('sjs-gallery-image');	
							
							// Выведем саму картинку
							s('.sjs-gallery-viewport',wrapper).append( n_img );
							
							// Сместим левый верхний угол контейнера на рассчитанную позицию
							wrapper.css( 'top', wrapperTop + "px" );																		
							
							// Обработка переключения между картинками
							var prevBtn = s('.sjs-gallery-prev');
							var nextBtn = s('.sjs-gallery-next');
							var viewPort = s('.sjs-gallery-viewport');							
							
							// Изменим размер центрального блока таблицы для "прижимания" к нему кнопок управления
							viewPort.css('width', n_img.width() + "px");								
							
							// Если это не первая картинка 
							if( image.index > 0 )
							{						
								// Обработчик переключения на предыдущую картинку
								prevBtn.click(function(){show_img( images.elements[ image.index - 1 ] );},true, true );
							}
							// Иначе скроем кнопку для перехода к предыдущей картинке
							else prevBtn.hide().parent().append('<span class="sjs-gallery-btn-empty"></span>');
							
							// Если это не последняя картинка 
							if( image.index < images.length - 1 )
							{
								// Обработчик переключения на следующую картинку
								nextBtn.click(function(){show_img( images.elements[ image.index + 1 ] );},true, true );
							}
							// Иначе скроем кнопку для перехода к следующей картинке
							else nextBtn.hide().parent().append('<span class="sjs-gallery-btn-empty"></span>');
							
							// Обработчик нажатия на фотографию для её переключения
							n_img.click(function( _image, options, event )
							{		
								s.trace('');
								s.trace('Нажал на фотку');
								
								
								// Если мы нажали по "парвой" части изображения - перейдем к следующей картинке
								if( event.offsetX  >= _image.width() / 2 ) show_img( images.elements[ _image.index + 1 ], _image );
								// Иначе к прошлой фотке
								else show_img( images.elements[ _image.index - 1 ], _image );	
													
							}, true, true );					
						}					
					};
					
					// Добавим порядковый номер картинке
					img.index = index++;					
											
					// Выполним инит картинке если она загружена
					if( img.loaded() ) init_image( img );
					
					// Повесим обработчик картинок галереи после их загрузки
					img.load( init_image );		
				});	
			}			
		}

        s.trace('Gallery23');
		
		// Вернем указатель на себя
		return this;
	}
};

// Добавим плагин к SamsonJS
SamsonJS.extend( SamsonJSGallery );
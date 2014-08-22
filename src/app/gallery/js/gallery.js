/**
 * Инициализировать галлерею
 */
var initGallery = function()
{
	// Выведем галлерею
	s('.gallery').gallery();
	
	// Обработчик загрузки картинки в галерею		
	s('.gallery-btn-edit').FormContainer({			
		placeMode : 'creatorOver',
		showHandler: function( form )
		{			
			// Создадим "перетаскиваемую" область для выделения части фотографии 
			var scaleArea = s('<div class="scale-area"></div>');	
			// Поле формы для хранения текущей ширины области выделения
			var widthField = s( '#Width', form );
			// Поле формы для хранения текущей высоты области выделения
			var heightField = s( '#Height', form );
			// Поле формы для хранения текущей координаты Х левого верхнего угла области выделения
			var leftField = s( '#Left', form );
			// Поле формы для хранения текущей координаты У левого верхнего угла области выделения
			var topField = s( '#Top', form );
			// Поле формы для "принудительной" установки ширины области выделения пользователем
			var uWidthField = s('#imageWidth');
			// Поле формы для "принудительной" установки высоты области выделения пользователем
			var uHeightField = s('#imageHeight');			
			
			var imgWidth = 0;
			var imgHeight = 0;
			// После загрузки картинки в редакторе
			s( '.image-editor img', form ).load(function( obj )
			{
				// Присоединим область вделения к документу
				s(document.body).append(scaleArea);
				
				// Инициалищзируем механизм "перетаскивания" созданной области по фотографии
				scaleArea.draggable({
					container : obj,
					updateHandler : function( draggableObject )
					{
						// Установим параметры объекта в поля формы
						widthField.val( draggableObject.width() );
						heightField.val( draggableObject.height() );
						leftField.val( draggableObject.relLeft );
						topField.val( draggableObject.relTop );					
						uWidthField.val( draggableObject.dragWidth );					
						uHeightField.val( draggableObject.dragHeight );
					}
				});	
				
				// Обработчик изменения ширины области выделения через текстовое поле
				uWidthField.change(function(input){scaleArea.update({width:input.val()});});				
				// Обработчик изменения высоты области выделения через текстовое поле
				uHeightField.change(function(input){scaleArea.update({height:input.val()});});	
				
				// Получим размер загруденной картинки
				imgWidth = s( '.image-editor img', form ).width();
				s('#imageNewWidth',form).val(imgWidth);				
				imgHeight = s( '.image-editor img', form ).height();
				s('#imageNewHeight',form).val(imgHeight);
				
			});
			s('#imageNewWidth',form).keyup(function(_input)
			{
				if (( s('#btnRateablyImage',form).a('checked') ))
				{
					originRatio = imgWidth / imgHeight;
					newHeight = parseInt(s('#imageNewWidth',form).val()) / originRatio;
					s('#imageNewHeight',form).val(Math.floor(newHeight));
				}
				
			});
			s('#imageNewHeight',form).keyup(function(_input)
			{
				if (( s('#btnRateablyImage',form).a('checked') ))
				{
					originRatio = imgWidth / imgHeight;
					newHeight = parseInt(s('#imageNewHeight').val()) * originRatio;
					s('#imageNewWidth').val(Math.floor(newHeight));
				}
			});
			s('#btnEditImage',form).click(function(){
				data = s('#GalleryID', form).val()+'|'+s('#imageNewWidth').val()+'|'+s('#imageNewHeight').val();
				s.ajax( 'gallery/ajax_scope/'+data, function( serverResponce ){
					s( '.image-editor img', form ).a('src',serverResponce);
				});
			});
			
			
			
		},
		// Обработчик отправки формы редактора изображения
		submitHandler : function( serverResponce, f, b ) 
		{ 
			// Преобразуем ответ от сервера в объект
			try{serverResponce = JSON.parse(serverResponce);}catch(e){};
			
			// Если не было ошибок при загрузке
			if ( !serverResponce.error)
			{
				s.trace(s('img',f.creator.parent()));
				galleryImg = s('img',f.creator.parent());
				galleryImg.a('src','');
				galleryImg.a('src',serverResponce.src);
			}
		},
		// Обработчик закрытия формы редактора изображения
		hideHandler : function()
		{
			// Уберем область выделения из документа
			s('div.scale-area').remove();
		}		
	});	
	
	// Обработчик удаления картинки из галереи
	s('.GalBtnDelete').click(function( link )
	{		
		// Попросим подтверждение
		if( confirm('Удалить фотографию?') )
		{
			// Выполним ассинхронный запрос на удаление картинки из галереи
			s.ajax( link.a('href'), function( serverResponce )
			{
				// Если не было ошибок при загрузке
				if ( !serverResponce.error )
				{	
					// Удалим эту картинку
					link.parent().remove();
				}
			});
		}
	}, true, true );		
};	


/**
 * Обработчик загрузки на сервер картинки 
 */
var uploadImgFinished = function( serverResponce, formSubmited, btnSubmit )
{
	// Преобразуем ответ от сервера в объект
	try{serverResponce = JSON.parse(serverResponce);}catch(e){};
	
	// Если не было ошибок при загрузке
	if ( !serverResponce.error)
	{			
		// Добавим картинку 
		s('.gallery').append( serverResponce.tag );
		
		// Выведем галлерею
		initGallery();
	}
};
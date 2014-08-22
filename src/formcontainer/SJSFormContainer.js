/**
 * SamsonJS FORM Container plugin
 * 
 * Расширение функционала JavaScript фреймоворка SamsonJS
 * для вывода всплывающиего контейнера формы c базовым функционалом
 * и поддержкой её ассинхронной отправки
 * 
 */
// TODO: Запоминать измененные размеры полей в куки, что-бы пользователю было удобно пользоваться
SamsonJS.fn.FormContainer = function( options )
{		
	// Обработаем все элементы из текущей выборки и создадим для них
	// объект-форму
	return this.each(function( creator )
	{		
		// Подменим создателя формы на текущий элемент из выборки
		options.creator = creator;
		
		// Создадим форму
		creator.formContainer = FromContainerObject( options );					
	});
};

/**
 * Объект - форма
 */
var FromContainerObject = (function( options ) 
{		
	var FromContainerObject = function( options ){ return new FromContainerObject.fn.init( options ); };
	
	// Указатель на глобальный контролирующий объект
	var _controller = FromContainerObject;
	
	// Коллекция открытых форм, принадлежащих данному объекту 
	_controller.openedForms = [];
	
	// Коллекция созданных форм, принадлежащих данному объекту 
	_controller.Forms = [];
	
	/**
	 * Объект формы
	 */
	FromContainerObject.fn = FromContainerObject.prototype = 
	{		
		constructor : FromContainerObject,
		/**
		 * Режим вывода формы
		 */
		placeMode : 'creatorBottom',
		
		/**
		 * Установить положение формы
		 */
		setFormPosition : function()
		{			
			// Получим положение элемента
			var offset = this.creator.offset();			
			
			// Расчетное положение формы
			var top = offset.top;
			var left = offset.left;	
			
			// В зависимости от режима вывода формы
			if( this.placeMode == 'creatorOver' )
			{
			
			}						
			else if( this.placeMode == 'creatorBottom' )
			{
				// Сместим элемент к нижней границе вызывающего 
				top += this.creator.height() + 1;
				
				// Добавим специальный стиль родительскому элементу
				this.creator.addClass('_form_opened_');
			}
			
			// Изменим положение формы относительно родительского элемента
			this.formContainer.css( 'top', top  + 'px' ); 
			this.formContainer.css( 'left', left + 'px' );						
		},
		
		/**
		 * Загрузить содержание формы
		 * @param clickedElement Элемент на который нажали
		 */
		loadForm : function( _form )
		{	
			// Если форма еще не открыта и задан контроллер для заполнения
			if( ! _form.opened && _form.fillerURL )
			{				
				// Выполним ассинхронный запрос на заполнения содержания формы
				s.ajax( _form.fillerURL, function( serverResponce )
				{
					// Если есть чем заполнить содержание формы
					if( serverResponce ) 
					{										
						// Создадим невидимый контейнер для формы
						_form.formContainer = s('<div class="form-id-' + _form.id + '" style="display:none;position:absolute;"></div>');
						
						// Устновим ему специальный CSS класс
						_form.formContainer.addClass( '_form-container_' );		
						
						// Прилепим форму к документу
						s(document.body).append(_form.formContainer);
						
						// Инициализируем форму
						FromContainerObject.fn.updateForm( _form, serverResponce );
					}
					
				});
			}
			else if(! _form.opened && _form.options.HTMLform)
			{
				// Создадим невидимый контейнер для формы
				_form.formContainer = s('<div class="form-id-' + _form.id + '" style="display:none;position:absolute;"></div>');
				
				// Устновим ему специальный CSS класс
				_form.formContainer.addClass( '_form-container_' );		
				
				// Прилепим форму к документу
				s(document.body).append(_form.formContainer);
				
				// Инициализируем форму
				FromContainerObject.fn.updateForm( _form, _form.options.HTMLform );
			}
		},
		
		/**
		 * Обновить содержание формы
		 * 
		 * @param _form Указатель на форму
		 * @param serverResponce Данные для обновления
		 */
		updateForm : function( _form, serverResponce )
		{				
			// Заполним форму
			_form.formContainer.html( serverResponce );	
			
			// Добавим эту функцию к форме
			_form.formContainer.updateForm = FromContainerObject.fn.updateForm;
			
			// Повесим обработчик закрытия формы
			s( '.close-button', _form.formContainer ).click( function( btnCloseForm )
			{
				// Вызовем обработчик скрытия формы
				_form.hideForm( btnCloseForm, _form );
				
			}, true, false );
			
			// Повесим обработчик отправки формы
			s( 'input[type=submit]', _form.formContainer ).click( function( btnSubmitForm )
			{				
				// Вызовем обработчик отправки формы
				_form.submitForm( btnSubmitForm, _form );
				
			}, true, true );
			
			// Покажем форму
			_form.showForm();
		},
		
		/**
		 * Отправить форму на сервер
		 * 
		 * @param btnCloseForm Указатель на кнопку которая инициировала отправку формы
		 */
		submitForm : function( btnSubmitForm )
		{			
			// Указатель на себя
			var _form = this;

			// Выполним отправку формы
			s( 'form', _form.formContainer ).ajaxForm( function( serverResponce )
			{	
				// Если задан обработчик этого события то вызовем его
				if( _form.options.submitHandler ) _form.options.submitHandler( serverResponce, _form, btnSubmitForm );
				
				// Скроем форму
				_form.hideForm();
			});
		},
		
		/**
		 * Скрыть форму
		 * 
		 * @param btnCloseForm Указатель на кнопку которая инициировала закрытие формы
		 */
		hideForm : function( btnCloseForm )
		{
			// Если задан обработчик этого события то вызовем его
			if( this.options.hideHandler ) this.options.hideHandler( this, btnCloseForm );
			
			// Уберем форму
			this.formContainer.remove();			
			
			// Установим флаг что форма закрыта
			this.opened = false;
			
			// Уменьшим количество открытых форм
			_controller.openedForms--;
		},		
		
		/**
		 * Отобразить форму
		 */
		showForm : function()
		{				
			// Если задан обработчик этого события то вызовем его
			if( this.options.showHandler ) this.options.showHandler( this );			
			
			// Спозифионируем форму
			this.setFormPosition();
						
			// Отобразим форму
			this.formContainer.show();		
			
			// Установим контейнер формы поверх всего остального
			this.formContainer.css('zIndex','999');			
			
			// Установим флаг что форма открыта
			this.opened = true;
			
			// Увеличим количество открытых форм
			_controller.openedForms++;
			
			// Если задан обработчик этого события то вызовем его
			if( this.options.showedHandler ) this.options.showedHandler( this );
		},
		
		
		/**
		 * Конструктор объекта формы
		 * @param creator Указатель на элемент, который должен обрабатывать отображение формы
		 * @param filler Контроллер для заполнения содержимого формы
		 * @returns Указатель на созданный объект-форму
		 */
		init : function( options )
		{		
			// Указатель на себя
			form = this;			
			
			// Получим параметры для формы
			var creator = this.creator = options.creator;
			//s.trace('init formcontainer'+creator.selector);
			// Если "создатель" существует
			if( creator )
			{					
				// Сохраним параметры объекта
				this.options = options;
				
				// Флаг что форма скрыта
				this.opened = false;
				
				// Контроллер для заполнения формы
				this.fillerURL = options.filler || creator.a('href');		
				
				// Установим режим вывода формы
				this.placeMode = options.placeMode || placeMode;
				
				// Сгенерируем уникальный идентификатор для формы
				this.id = options.id || Math.floor( Math.random() * 212423452355235 );															
				
				// Повесим базовые события на форму
				
				// При нажатии на "создателя" отобразить форму
				creator.click( function( clickedElement, eventOptions ){ FromContainerObject.fn.loadForm( eventOptions ); }, true, true, form );			
				
				// Добавим созданную форму в глобальный контролирующий объект
				_controller.Forms.push( this );	
			}
		}
	};	
	
	// Подвяжем прототип к функции создания
	FromContainerObject.fn.init.prototype = FromContainerObject.fn;
	
	// Вернем себя что бы появится
	return FromContainerObject;
})();	
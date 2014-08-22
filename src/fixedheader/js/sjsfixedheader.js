/**
 * SamsonJS FixedHeader plugin 
 */
var SamsonJSFixedHeader = 
{	
	fixedHeader : function()
	{			
		// Указатель на самого себя
		var _self = this;
		
		/* Initialize plugin */
		var init = function()
		{	
			// Destroy all old clones
			s('.__fixedHeaderClone').remove();
			
			// Parent
			var parent = _self.parent();
			
			// THEAD columns 	
			var THs = s('thead th',_self);
			
			// Если есть элементы DOM в выборке
			if ( _self.length && _self.height() > parent.height() && _self.width() <= parent.width())
			{				
				// Clone whole table
				var _clone = _self.clone();	
				
				// Mark clone with special class
				_clone.addClass('__fixedHeaderClone');
				
				// Get cloned table headers
				var cTHs = s('th',_clone); 
				
				// Remove TBODY part
				s('tbody tr', _clone).remove();
				
				// Set table auto width
				_clone.css('width','auto');	
				_clone.css('top',_self.offset().top+'px');
				
				// Set real columns width
				for(var i=0; i<THs.length; i++) cTHs.elements[i].css( 'width',THs.elements[i].width()+1+'px');
						
				// Append clone to document
				parent.append(_clone);			
				
				// Добавим класс для правильного отображения
				_clone.addClass('sjs-fixedheader');			
				
				// Повесим событие на отображение клона при скроле окна
				s(window).bind({
					EventName: 'scroll',
					EventHandler: function(obj, options, e)
					{
						//_clone.show(); 
					}		
				});
			}	
		}
		
		// Init plugin
		init();
		
		// Set window resize handler
		s(window).resize(init);
	}
};

// Добавим плагин к SamsonJS
SamsonJS.extend( SamsonJSFixedHeader );
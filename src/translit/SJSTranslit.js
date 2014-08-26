/**
 * SamsonJS Translit plugin 
 */
var SamsonJSTranslit = 
{	
	translit : function( )
	{
		if (this.length)
		{
			// Составим словарь для транслита
			var _dict = 
			{	
				'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 
	            'ж':'zh', 'з':'z', 'и':'i', 'й':'j', 'к':'k',
	            'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r',
	            'с':'s', 'т':'t', 'ы':'u', 'ф':'f', 'х':'h', 'ц':'ts',
	            'ч':'ch', 'ш':'sh', 'щ':'sch', 'у':'y',
	            'ь':'', 'ю':'ju', 'я':'ja', 'ї':'gi',
	            'ъ':'i', 'ё':'je', 'і':'i', 'є':'e', 'э':'e' 
            };
			
			var out_text = '';	
			
			//получим значение инпута, переконвертив его в нижний регистр
			var input_text = this.val().toLowerCase();			
			
			input_text = input_text.replace(/[\s]+/,' ');
			//переберем все символы стоки
			for (var i=0; i<input_text.length; i++)
			{
				var current_char = input_text[i];
				//если существует соответствующий символ в словаре заменим его
				if (_dict[ current_char ]) out_text = out_text + _dict[ current_char ];
				//если это пробел заменим его на '_'
				else if( current_char == ' ' ) out_text = out_text +'-';
				// в остальных случаях оставим его без изменения
				else out_text = out_text + current_char;			
			}
			// уберем все символы кроме допустимых
			out_text = out_text.replace(/[^a-z0-9\-\_]/g, '');

			// У    берем ненужные тире
			while (out_text.indexOf('--') != -1)out_text = out_text.split('--').join('-');
			
			//out_text.replace(new RegExp('--', "g"), '-');
			
			return out_text;
		}			
	}
};

//Добавим плагин к SamsonJS
SamsonJS.extend( SamsonJSTranslit );
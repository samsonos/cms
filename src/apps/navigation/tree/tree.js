function StructureTree()
{
	// Флаг нажатия на кнопку управления
	var ControlFormOpened = false;

	// Указатель на текущий набор кнопок управления
	var ControlElement = null;

	/**
	 * Инициализировать дерево ЭСС
	 */
	var init = function( html )
	{
		// Если передано HTML - заполним дерево
		if( html && html.length ) s( '.tree-container' ).html( html );


		// Обработчик отображения/скрытия кнопок управления ЭСС
		s( '.structure-element' )
		.mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
		.mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });


		// Выведем дерево ЭСС
		s( '.tree-container' ).treeview();
	};

	// Инициализируем дерево ЭСС
	init();
}

/**
 * Инициализация модуля ЭСС
 */
s('#structure').pageInit( StructureTree );
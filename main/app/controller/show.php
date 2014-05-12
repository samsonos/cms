<?php
/**
 * Контроллер по умолчанию
 */
function show()
{
	// Установим представление
	m()->view('show')->title('Просмотр сайта');
	
	// Если есть данные о страром просмотре
	if( isset($_SESSION['_show_location']) )
	{
		m()->set( 'location', $_SESSION['_show_location'] );
	}
}

/**
 * Ассинхронный обработчик сохранения текущей просматриевамой страницы
 */
function show_ajax_history()
{
	// Ассинхронность
	m()->async(TRUE);
	
	// Если передан url
	if( isset($_POST['url']{0}) )
	{
		// Запишем данные о текущей просматриевамой странице
		$_SESSION['_show_location'] = $_POST['url'];
	}	
}
?>
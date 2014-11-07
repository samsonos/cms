<?php
/** Контроллер включения редактора содержимого сайта */
function editor()
{
	// Установим флаг редактирования
	$_SESSION['__CMS_EDITOR__'] = true;
	
	// Сформируем путь к сайту с учетом текущего материала
	$url = 'http://' . $_SERVER['SERVER_NAME'] . '/'.(isset($material)?$material:'');
	
	// Перейдем к форме авторизации
	header('Location: ' . $url );	
}
?>
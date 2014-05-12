<?php
/**
 * Контроллер для управления выходом из системы
 */
function control_logout( $url = NULL )
{
	// Уберем флаг редактирования
	unset($_SESSION['__CMS_EDITOR__']);
	
	// Выполним стандартный выход
	auth2_logout( $url );
}

/** Контроллер для просмотра содержимого сайта */
function control_site()
{
	// Уберем флаг редактирования
	unset($_SESSION['__CMS_EDITOR__']);
	
	// Сформируем путь к сайту с учетом текущего материала
	$url = 'http://' . $_SERVER['SERVER_NAME'] . '/'.(isset($material)?$material:'');
	
	// Перейдем к форме авторизации
	header('Location: ' . $url );
}

/** Контроллер включения редактора содержимого сайта */
function control_editor()
{
	// Установим флаг редактирования
	$_SESSION['__CMS_EDITOR__'] = true;

	// Сформируем путь к сайту с учетом текущего материала
	$url = 'http://' . $_SERVER['SERVER_NAME'] . '/'.(isset($material)?$material:'');

	// Перейдем к форме авторизации
	header('Location: ' . $url );
}
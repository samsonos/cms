<?php
use \samson\cms\App;

/**
 * Контроллер по умолчанию для главной страницы
 */
function main()
{
	// Представление для главной
	$result = '';

	// Render application main page block
	foreach (App::loaded() as $app) {
        // Show only visible apps
        if ($app->hide == false) {
            $result .= $app->main();
        }
    }
		
	// Установим представление
	m()	->view('main')
		->title(t('Главная', true))
		->result( $result );
}
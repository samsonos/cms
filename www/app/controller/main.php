<?php
use \samson\cms\App;

/**
 * Контроллер по умолчанию для главной страницы
 */
function main()
{
	// Представление для главной
	$result = '';

    if (!m('social')->authorized()) {
        url()->redirect('signin');
    }
	
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

//function main_changepass()
//{
//    $user = m('social')->user();
//    if (!m('social')->authorized()) {
//        url()->redirect('signin');
//    }
//    s()->template('app/view/main.php');
//
//    $aside = m()->view('cabinet/edit-company/aside')->passactive('-active')->output();
//
//    m()->view('cabinet/edit-company/change_password')->title('Изменение пароля')->aside($aside);
//}
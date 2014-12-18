<?php
use \samson\cms\App;

/** Universal controller action */
function default__HANDLER()
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
	
	// Set home icon active	
	m()->mainPageActive('active');
	
	// Установим представление
	m()	->view('main')
		->title(t('Главная', true))
		->result( $result );
}

/** E404 controller action */
function default_e404()
{
    m()->view('e404')->title(t('Страница не найдена', true));
}

/* Menu controller action */
function default_menu()
{
    $result = '';
    // Iterate loaded samson\cms\application
    foreach (App::loaded() as $app/*@var $app App*/) {
        // If application is not hidden
        if ($app->hide == false) {
            // Render application menu item
            $result .= m()
                ->active( url()->module == $app->id() ? 'active' : '' )
                ->app( $app )
                ->name( isset($app->name{0}) ? $app->name : (isset($app->app_name{0})?$app->app_name:''))
                ->output('menu/item');
        }
    }

    $subMenu = '';

    // Find current SamsonCMS application
    if (App::find(url()->module, $app/*@var $app App*/)) {
        // Render main-menu application sub-menu
        $subMenu = $app->submenu();

        // If module has sub_menu view - render it
        if ($app->findView('sub_menu')) {
            $subMenu .= $app->output('sub_menu');
        }
    }

    // Render menu view
    m()
        ->view('menu/index')
        ->submenu($subMenu)
        ->items( $result);
}
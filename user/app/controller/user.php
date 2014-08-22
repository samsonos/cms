<?php
/**
 * Базовый контроллер
 */
function user()
{
	$query = dbQuery('user')->Active(1);
	$table = new samson\cms\web\user\Table($query);
	// Установим представление
	m()->view('index')->title(t('Пользователи системы', true))
	// Установим шаблон таблицы пользователей
	->set( 'user_table',$table->render() );	
}

/**
 * Контроллер для вывода формы редактирования/создания пользователя
 * 
 * @param string $user_id Идентификатор польщователя
 * @param string $group_id Идентификатор группы пользователя
 */
function user_form( $user_id = 0, $group_id = NULL )
{
	
	// Безопасно получим указатель на пользователя
	if( dbQuery('user')->UserID($user_id)->first($db_user ) )
	{
		// Установим пользователя
		m()->title(t('Редактирование', true).': ' . mdl_user_short_name($db_user) )->set( 'db_user', $db_user );
	}
	// Выведем форму для нового пользователя
	else m()->title(t('Создание пользователя', true));

	
	// Создадим пустышку пользователя
	$db_user = uniset( $db_user, new \samson\activerecord\user( FALSE ));
	
	// Установим пользователя
	m()->set( $db_user )->view('form/tmpl');;
}

/**
 * Ассинхронный контроллер для вывода формы пользователя
 * 
 * @param string $user_id Идентификатор польщователя
 * @param string $group_id Идентификатор группы пользователя
 */
function user_ajax_form( $user_id = 0, $group_id = NULL ) 
{
	// Ассинхронный контроллер
	s()->async(TRUE);
	
	// Вызовем стандартный контроллер формы
	user_form( $user_id, $group_id );	
	
	// Выведем представление формы, и передадим в него все параметры из текущего модуля
	echo m()->output( 'app/view/form/tmpl.vphp' );
}

/**
 * Ассинхронный контроллер для сохранения данных пользователя
 */
function user_ajax_save()
{
	s()->async(TRUE);
	mdl_user_save($_POST);
	echo json_encode(array( 'data'=> mdl_user_table() ));
}

/**
 * Ассинхронный обработчик удаления пользователя
 * 
 * @param $user_id Идентификатор пользователя
 */
function user_ajax_delete( $user_id = NULL){echo samson_ajax_handler( 'mdl_user_delete', $user_id, 'mdl_user_table' );}

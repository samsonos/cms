<?php
/**
 * Базовый контроллер
 */
function comment()
{
	// Установим представление
	m()->view('index')->title('Коментарии')	
	// Установим шаблон таблицы пользователей
	->set( 'comment_table', mdl_comment_table() );	
}

/**
* Контроллер для вывода формы редактирования комментария
*
* @param string $comment_id Идентификатор комментария
*/
function comment_form( $comment_id )
{
	// Получим имя локализации
	$locale = locale();	

	// Безопасно получим указатель на пользователя
	if( dbSimplify::parse( $locale.'comment', $comment_id, $db_comment ) )
	{
		// Установим представление формы
		m()->view('form/tmpl');
		// Установим пользователя
		m()->title('Редактирование комментария:')->set( 'db_user', $db_comment );
		// Установим дополнительный контекст формы
		m()->set( $db_comment );
	}	
}

function comment_ajax_form( $comment_id )
{
	// Ассинхронный контроллер
	s()->async(TRUE);

	// Вызовем стандартный контроллер формы
	comment_form( $comment_id );

	// Выведем представление формы, и передадим в него все параметры из текущего модуля
	echo m()->output( 'app/view/form/tmpl.php' );
}

/**
* Ассинхронный контроллер для сохранения данных комментария
*/
function comment_ajax_save(){
	echo ajax_handler( 'mdl_comment_save', array($_POST), 'mdl_comment_table', 'app/view/table/tmpl.php' );
}

/**
 * Ассинхронный обработчик удаления комментария
 *
 * @param $comment_id Идентификатор комментария
 */
function comment_ajax_delete( $user_id = NULL){
	echo samson_ajax_handler( 'mdl_comment_delete', $comment_id, 'mdl_comment_table', 'app/view/table/tmpl.php' );
}
?>
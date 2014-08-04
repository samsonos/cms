<?php 
/**
 * Вывести таблицу пользователей из переданной коллекции 
 * 
 * @param array $db_users Коллекция пользователей из БД
 * @return string HTML таблицу пользователей
 */
function mdl_comment_table($table_template = 'app/view/table/tmpl.vphp' )
{	
	$comments_query = dbQuery('comment')->cond('Active', 1)->join('material')->order_by('Created','DESC');
	$html = '';
	if ( dbSimplify::query($comments_query->exec(), $comments, true) )
	{
		foreach ( $comments as $comment ) $html .= mout()->set($comment)->output('app/view/table/row/tmpl.vphp');
	}

	// Вернем HTML представление таблицы
	return mout()->set('comment_rows', $html)->output($table_template);;
}

function mdl_comment_save( array $_data, & $status = NULL )
{
	// Проверим входніе данные
	if( !is_array( $_data ) || !sizeof($_data) ) return FALSE;

	// Проверим жетон формы на валидность
	if( !auth()->verify_token( $_data['token'], 'comment_form' ) ) return FALSE;
	
	// Установим "особые" поля
	$_data['Created'] 		= ( $_data['Created'] == 0 ) ? date('Y-m-d H:i:s') : $_data['Created'];
	$_data['Active']		= 1;
	$locale = locale();
	// Попытаемся получить пользователя по её ID
	if (dbSimplify::parse( $locale.'comment', $_data['CommentID'], $db_comment ))
	{
		$db_comment->Text = $_data['Text'];
		$db_comment->Created = $_data['Created'];
		$db_comment->Active = $_data['Active'];
		$db_comment->save();
		return TRUE;
	}
	return FALSE;
}
?>
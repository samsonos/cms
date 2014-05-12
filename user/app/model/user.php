<?php 
use Samson\ActiveRecord\dbSimplify;
use samson\activerecord\dbQuery;

/**
 * Получить всех пользователей 
 */
function mdl_user_all()
{
	return _User()->find_all_by_Active(1);
}

function mdl_user_table()
{
	$query = dbQuery('user')->Active(1);
	$table = new samson\cms\table\Table($query);
	return $table->render();
}

/**
 * Обработчик сохранения данных пользователя системы
 * 
 * @param array $_data 	Коллекция данных о пользователе
 * @param array $status Статус выполнения сохранения польтзователя
 * @return boolean Результат выполнения метода
 */
function mdl_user_save( array $_data, & $status = NULL ) 
{	

	//if ( ! mdl_permission('User_Save') ) return FALSE;
	// Проверим входніе данные
	if( !is_array( $_data ) || !sizeof($_data) ) return FALSE; 
	
	// Установим "особые" поля
	$_data['Created'] 		= ( $_data['Created'] == 0 ) ? date('Y-m-d H:i:s') : $_data['Created'];
	$_data['md5_Password'] 	= md5($_data['Password']);
	$_data['md5_Email'] 	= md5($_data['Email']);
	$_data['Active']		= 1;
	
	if (!dbQuery('user')->UserID($_data['UserID'])->Active(1)->first($db_user)) $db_user = new \samson\activerecord\user(false);
	
	foreach ($_data as $item=>$value)if(isset($db_user->$item)) $db_user->$item = $value;

	$db_user->save();
	
	auth()->update( $db_user );
	
	return true;	
}

/**
 * Обработчик даления данных пользователя системы
 * 
 * @param mixed $db_user Указатель на пользователя
 * @param string $status Указатель на ошибки выполнения действия
 * @return boolean Результат удаления пользователя
 */
function mdl_user_delete( $user_id, & $status = NULL  )
{
	//if ( ! mdl_permission('User_Delete') ) return FALSE;
	// Безопасно получим пользователя
	if ( dbQuery('user')->UserID($user_id)->first($db_user) )
	{
		// Пометим его как удаленн
		$db_user->Active = 0;
		
		// Сохраним изменения в БД
		$db_user->save();
		
		//Очистим кеш
		//Cache::url_free();
		
		// Все ок
		return TRUE;
	}
	
	// Дошли сюда, значит что-то не так
	return FALSE;
}

/**
 * Получить короткое имя пользователя
 * @param mixed $db_user 	Указатель на пользователя
 * @return string Короткое имя пользователя
 */
function mdl_user_short_name( $user = NULL )
{
	if( !isset( $user ) ) $user = & auth()->user;	
	else $user = dbQuery('user')->id($user->id)->first();
		
	if( isset( $user ) )
	{			
		if (isset($user->SName{0}))
		{ 
			$str = $user->SName;
			if (isset($user->FName{0})) $str .= ' ' . substr ( $user->FName, 0, 2 ) . '.'; 
			if (isset($user->SName{0})) $str .= ' ' . substr ( $user->TName, 0, 2 ) . '.';
			return $str;
		}	
		else if (isset($user->FName{0})) return $user->FName;
		else if (isset($user->TName{0})) return $user->TName;
	}
}


/**
 * Получить полное имя пользователя
 * 
 * @param mixed $db_user 	Указатель на пользователя
 * @return string Полное имя пользователя
 */
function mdl_user_full_name( $user )
{
	//Если получилли указатель на пользователя
	if ( dbQuery('user')->first( $user ) )
	{
		if (isset($user->SName{0}))
		{
			$str = $user->SName;
		}
		if (isset($user->FName{0}))
		{
			$str .= ' ' . $user->FName;
		}
		if (isset($user->TName{0}))
		{
			$str .= ' ' . $user->TName;
		}
		return $str;
	}
}

/**
 * Сгенерировать поле для выбора группы пользователя
 * учитывая его текущую группу
 * 
 * @param string $group_id
 */
function mdl_user_html_group_select( $db_user )
{
	// Определим "выбранную" группу в списке
	$group_id = 0;
	
	if( dbSimplify::parse( 'user', $db_user, $db_user) ) $group_id = $db_user->GroupID;
	
	// Результат
	$html = '<option value="0">Без группы</option>';
	
	// Переберем все группы пользователей
	if( dbSimplify::query( _group()->all(), $db_groups, true) )	foreach ( $db_groups as $db_group ) 
	{
		$html .= '<option ' . ( $db_group->id == $group_id ? 'selected' : '') . ' value="' . $db_group->id . '">' . $db_group->Name . '</option>';
	}
	
	// Вернем результат
	return $html;
}
?>
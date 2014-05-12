<?php
use samson\cms\field\Table;


use Samson\ActiveRecord\dbSimplify;
use Samson\ActiveRecord\structurefield;

/** Render SamsonCMS Field view */
function mdl_field_render( $nav = '0', $page = 1 )
{
	// Создадим пейджиризатор
	$pager = new samson\pager\Pager( $page, 10, 'field/'.$nav.'/' );
	
	// Create SamsonCMS fields table
	$table = new Table( $pager );
	
	// Render view
	return m()	
	->title( 'Дополнительные поля' )
	->set( 'table', $table->render() )
	->set( $pager )
	->output('index');
}

/**
 * Cохранения поля ЭСС в БД
 *
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 * @return boolean Результат выполнения сохранения поля ЭСС в БД
 */
function mdl_field_save( $structure_id, $field_id = 0 )
{
	// Если передан идентификатор ЭСС в БД
	if( dbQuery('structure')->StructureID($structure_id)->first($db_structure ) )
	{
		// Выполним попытку разпознания переданного указателя на поле ЭСС
		dbQuery('field')->FieldID($field_id)->first($db_field );
		
		// Установим дополнительные поля
		$_POST['Active'] = 1;
		
		// Уберем пробелы из имени поля
		$_POST['Name'] = trim($_POST['Name']);

		// Создадим новое поле/сохраним изменения в поле ЭСС
		if( dbSimplify::save('field', $db_field, $_POST ) )
		{
			// Получим существующие связи поля с ЭСС
			$existing_connections = _structurefield()->find_all_by_FieldID_and_StructureID( $db_field->id, $db_structure->id );			
				
			// Если связь между полем и ЭСС еще не существует
			if( ! dbSimplify::query( $existing_connections, $db_connection ) )
			{
				// Создадим новую запись о связи материала с ЭСС
				$db_connection = new StructureField();
			}
			
			// Запишем параметры связи в БД
			$db_connection->FieldID 	= $db_field->id;
			$db_connection->StructureID = $db_structure->id;
			$db_connection->Active		= 1;
			$db_connection->save();
			
			// Все прошло гуд
			return TRUE;
		}		
	}
	
	// Ниче не вышло
	return FALSE;
}

/**
 * Удаление поля ЭСС в БД
 *
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
function mdl_field_delete( $structure_id, $field_id  )
{
	// Получим существующие связи поля с ЭСС
	if ( dbSimplify::query(_structurefield()->find_all_by_FieldID_and_StructureID_and_Active( $field_id, $structure_id, 1 ), $existing_connections, true) )
	{
		// Удалим все существующие связи
		for ($i = 0; $i < sizeof($existing_connections); $i++) 
		{
			$existing_connections[ $i ]->Active = 0;
			$existing_connections[ $i ]->save();
		}
	}
}


/**
 * Сгенерировать элемент формы для выбора типа поля ЭСС
 * Если передан указатель на поле, то в элементе формы будет указан текущий тип поля
 * 
 * @param mixed $field_id Указатель на поле ЭСС
 */
function mdl_field_html_select( $field_id )
{
	// Тип переданного поля - тип по умолчанию - текст
	$db_field_type = 0;
	
	// Безопасно попытаемся определить переданный указатель поля ЭСС в БД
	if( dbSimplify::parse( 'field', $field_id, $db_field ) ) $db_field_type = $db_field->Type;	
	
	// Соберем представление элемента формы
	$html = '';
	
	// Определим возможные типы полей
	$types_data = array( 'Текст' => 0, 'Ресурс' => 1 ,'Select' => 4, 'Таблицы' => 5, 'Дата' => 3, 'Материал' => 6);
	
	foreach ($types_data as $k => $v ) 
	{
		// Аттрибут для выделения типа переданного поля в элементе формы
		$selected = ($db_field_type == $v) ? 'selected' : '';
		
		// Сгенерируем HTML представление типа поля
		$html .= '<option value="' . $v . '" ' . $selected . '>' . $k . '</option>';
	}
	
	// Вернем HTML элемент формы
	return '<select name="Type" id="Type">' . $html . '</select>';	
}

?>
<?php
/**
 * Преобразовать дату для вывода.
 * Если дата попадает в сегодня то выводится только время
 * Если дата не сегодняшняя то выводится только дата
 * @param string $_date	Дата которую нужно преобразовать
 */
function CMS_make_date( $_date )
{
	$unix_time = strtotime( $_date );
	$today = date( 'Y-m-d' );
	
	if( date( 'Y-m-d', $unix_time ) == $today ) $unix_time = date( 'H:i', $unix_time );
	else $unix_time= date( 'd.m.Y', $unix_time );
	
	return $unix_time;
}

/**
 * Преобразовать данные для вывода
 *
 * @param string $text Строка для преобразования
 * @return string Преобразованная строка
 */
function CMS_format( $text )
{
	return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
}

/**
 * Универсальный генератор "содержания" для HTML элемента формы SELECT
 * по данным из таблицы БД
 * 
 * @param string $class_name Класс объекта БД для заполнения
 * @param string $object_id Идентификатор или объект который должен быть выбран в списке
 * @return string HTML содержание элемента формы SELECT
 */
/*
function samson_html_db_select( $class_name, $object_id )
{	
	// Есди передано перечисление
	if( strpos( $class_name, ',') ) 
	{
		// Сформируем список данных
		$data = array();
		
		// Разобьем значения
		foreach ( explode( ',', $class_name ) as $value ) 
		{
			// Разобьем на Значение : Представление
			$value = explode( ':', $value );
			
			// Сформируем данные для HTML 
			$data[] = array( 'id' => $value[0], 'Name' => $value[1] );
		}

		// Вернем HTML select
		return samson_html_select( $data, $object_id );	
	}
	
	// Попытаемся "универсально" определить идентификатор переданного,выбранного объекта
	// если нам передан сам объект - получим его идентификатор
	if( dbSimplify::parse( $class_name, $object_id, $db_object) ) $object_id = $db_object->id;	
	
	// Получим обработчик запросов к переданному классу БД
	$db_selector = '_' . $class_name;	
	$db_selector = $db_selector();	
	
	// Коллекция данных
	$data = array( 
		array( 'id'=>0, 'Name' => '') // Добавим пустышку 
	);	
		
	// Переберем все группы пользователей
	foreach ( $db_selector->find_all_by_Active(1) as $db_object )
	{		
		$data[] = array(
			'id' => $db_object->id,
			'Name' => $db_object->Name,
			'Description' => $db_object->Description
		);		
	}

	// Вернем результат
	return samson_html_select( $data, $object_id );
}
*/
/**
 * Универсальный генератор "содержания" для HTML элемента формы SELECT
 *
 * @param array $data Коллекция данных для вывода
 * @param string $object_id Идентификатор или объект который должен быть выбран в списке
 * @return string HTML содержание элемента формы SELECT
 */
/*
function samson_html_select( array $data = NULL, $object_id )
{
	// Если передан собственный набор данных
	if( isset( $data ) && is_array( $data ) )
	{
		// Результат
		$html = '';

		// Переберем все группы пользователей
		foreach ( $data as $db_object )
		{
			$data['Description'] = isset($data['Description'])?$data['Description']:'';
			$html .= '<option title="' . $data['Description'] . '"' . ( $db_object['id'] == $object_id ? 'selected' : '') . ' value="' . $db_object['id'] . '">' . $db_object['Name'] . '</option>';
		}
	
		// Вернем результат
		return $html;
	}
}
*/
?>
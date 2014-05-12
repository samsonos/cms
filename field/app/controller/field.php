<?php

use Samson\ActiveRecord\dbSimplify;
use Samson\ActiveRecord\field;

/** Universal async renderer */
function field_ajax( $nav = '0', $page = 1 )
{
	s()->async( true );
	
	echo mdl_field_render( $nav, $page );
}


/** Универсальный контроллер */
function field__HANDLER( $nav = '0', $page = 1, $async = 0 )
{		
	if( !$async ) m()->html( mdl_field_render( $nav, $page ) )->title( 'Дополнительные поля' );
	else 
	{	
		s()->async( true );
		
		echo mdl_field_render( $nav, $page );
	}
}

/**
 * Контроллер для отображения спика полей ЭСС
 * 
 * @param string $structure_id Идентификатор элемента структуры в БД
 */
function field_ajax_list( $structure_id, $echo = TRUE )
{
	// Ассинхронный ответ
	s()->async(TRUE);	

	// Попытаемся найти ЭНС
	if( ifcmsnav( $structure_id, $db_structure, 'id' ) )
	{
		// Сформируем параметры для представления
		$view_data = array( 'db_structure' => $db_structure );
		
		// Получим все поля связанные с данным ЭСС
		$items = _cmsnavfield()->find_all_by_StructureID_and_Active( $db_structure->id, 1 );
		
		// Получим записи полей из БД
		if (sizeof($items) > 0)	$view_data['items'] = _field()->find_all_by_FieldID( dbSimplify::implode( $items, 'FieldID'));
		
		m()->set($view_data);
		
		$result = m()->output('app/view/list.php');
		
		// Подгрузим шаблон с формой
		if ($echo) echo $result;
		else return $result;
	}
}

/**
 * Контроллер для получения формы редактирования/создания поля для ЭСС
 * 
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
function field_ajax_form( $structure_id, $field_id = NULL  )
{
	// Ассинхронный ответ
	s()->async(TRUE);
	
	// Попытаемся найти ЭНС
	if( ifcmsnav( $structure_id, $db_cmsnav, 'id' ) )
	{
		// Установим контекст вывода для текущего модуля
		m()->set( $db_cmsnav )->set( 'type_select', mdl_field_html_select( $field_id ) );
		
		// Если передан идентификатор поля ЭСС - передадим его для редактирования
		if( dbSimplify::parse( 'field', $field_id, $db_field)) m()->set( new Field( $field_id ) );
		
		// Подгрузим шаблон с формой
		echo m()->output('app/view/form.php');
	}
}

function field_ajax_clone($structure_id)
{
	mdl_structure_clone_parent_fields($structure_id);
	// Выведем новый(Обновленный) список элементов
	$result['data'] = field_ajax_list( $structure_id, FALSE );
	echo  json_encode($result);
}

/**
 * Контроллер для ассинхронного сохранения поля ЭСС в БД
 * Объединяет в себе две функции - сохранени изменений в списке полей ЭСС
 * и вывод самого списка полей ЭСС, для экономии запросов к серверу 
 *
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
function field_ajax_save( $structure_id, $field_id = NULL )
{
	// Ассинхронный ответ
	s()->async(TRUE);

	// Выполним синхронный кнотроллер
	field_save( $structure_id, $field_id );
	
	// Выведем новый(Обновленный) список элементов
	$result['data'] = field_ajax_list( $structure_id, FALSE );
	echo  json_encode($result);
}

/**
 * Контроллер для ассинхронного удаления поля ЭСС в БД 
 *
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
function field_ajax_delete( $structure_id, $field_id  )
{
	// Ассинхронный ответ
	s()->async(TRUE);
	
	// Удалим данные связи и сам элемент
	mdl_field_delete( $structure_id, $field_id  );
	
	// Выведем новый(Обновленный) список элементов
	$result['data'] = field_ajax_list( $structure_id, FALSE );
	echo  json_encode($result);
}


/**
 * Контроллер для сохранения поля ЭСС в БД
 * 
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
function field_save( $structure_id, $field_id = NULL )
{
	// Выполним попытку сохранения поля ЭСС в БД
	if( ! mdl_field_save( $structure_id, $field_id ) )
	{
		// Обработчик ошибки создания нового поля ЭСС
		trace('Ошибка создания нового поля для: "' . $db_structure->Name . '"');	
	}		
}
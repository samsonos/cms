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
function field_async_ajax_list($structure_id)
{
    $return = array('status' => 0);
    // Попытаемся найти ЭНС
    if (ifcmsnav($structure_id, $db_structure, 'id')) {
        $fields = dbQuery('\samson\cms\CMSField')->join('\samson\cms\CMSNavField')->cond('StructureID', $structure_id)->exec();
        $items = '';
        if (sizeof($fields)) {
            foreach ($fields as $field) {
                $items .= m()->view('form/field_item')->field($field)->structure($db_structure)->output();
            }
        } else {
            $items = m()->view('form/empty_field')->output();
        }

        $html = m()->view('form/field_list')->structure($db_structure)->items($items)->output();

        $return['status'] = 1;
        $return['html'] = $html;
    }

    return $return;
}

/**
 * Контроллер для получения формы редактирования/создания поля для ЭСС
 * 
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
function field_async_form( $structure_id, $field_id = NULL  )
{
    $return = array('status' => 0, 'html' => '');
    // Попытаемся найти ЭНС
    if (ifcmsnav($structure_id, $db_cmsnav, 'id')) {
        $return['status'] = 1;
        // Установим контекст вывода для текущего модуля
        m()->set($db_cmsnav)->set('type_select', mdl_field_html_select($field_id));

		//if( dbSimplify::parse( 'field', $field_id, $db_field)) m()->set( new Field( $field_id ) );

        if (dbQuery('field')->id($field_id)->first($db_field)) {
            m()->set($db_field);
        }
        // Подгрузим шаблон с формой
        $html = m()->output('app/view/form.php');

        $return['html'] = $html;
    }

    return $return;
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
function field_async_save( $structure_id, $field_id = NULL )
{
	// Выполним синхронный кнотроллер
	//field_save( $structure_id, $field_id );
	
	// Выведем новый(Обновленный) список элементов
	/*$result['data'] = field_ajax_list( $structure_id, FALSE );
	echo  json_encode($result);*/

    if (!dbQuery('\samson\cms\web\CMSField')->id($field_id)->first($field)) {
        $field = new \samson\cms\web\CMSField(false);
    }

    // Update field data
    $field->update($structure_id);

    return array('status' => 1);
}

/**
 * Controller for deleting structurefield relation
 * @param int $structure_id CMSNav identifier
 * @param int $field_id CMSField identifier
 *
 * @return array Ajax response
 */
function field_async_delete($structure_id, $field_id)
{
    /** @var \samson\cms\CMSNavField $relation */
    if (dbQuery('structurefield')->FieldID($field_id)->StructureID($structure_id)->first($relation)) {
        $relation->delete();
    }
	// Удалим данные связи и сам элемент
	//mdl_field_delete( $structure_id, $field_id  );
	
	// Выведем новый(Обновленный) список элементов
	//$result['data'] = field_ajax_list( $structure_id, FALSE );
	//echo  json_encode($result);
    return array('status' => 1);
}


/**
 * Контроллер для сохранения поля ЭСС в БД
 * 
 * @param string $structure_id Идентификатор элемента структуры в БД
 * @param string $field_id	Идентификатор поля в БД
 */
/*function field_save( $structure_id, $field_id = NULL )
{
	// Выполним попытку сохранения поля ЭСС в БД
	if( ! mdl_field_save( $structure_id, $field_id ) )
	{
		// Обработчик ошибки создания нового поля ЭСС
		trace('Ошибка создания нового поля для: "' . $db_structure->Name . '"');	
	}		
}*/
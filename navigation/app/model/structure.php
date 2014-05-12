<?php
use Samson\ActiveRecord\dbRelation;
use samson\cms\CMSNav;
use samson\cms\CMSMaterial;
use samson\cms\CMSMaterialField;
use samson\cms\CMSNavField;
use Samson\ActiveRecord\user;
use Samson\ActiveRecord\gallery;
use Samson\ActiveRecord\dbSimplify;

/**
 * Получить поля принадлежащие конкретному ЭСС
 *
 * @param mixed $db_structure Указатель на ЭСС
 * @return array Коллекцию записей из БД описывающих поля ЭСС
 */
function mdl_structure_fields( $db_structure )
{
	// Безопасно получим указатель на ЭСС в БД
	if( ifcmsnav( $db_structure, $db_structure ))
	{
		return $db_structure->fields();
	}
}

/**
 * Сохранить элемент структуры в БД
 * @param array $_data Коллекция данных о элементе структуры
 */
function mdl_structure_save( $_data )
{
	// Получим родительский ЭСС
	//'samson\cms\cmsnav', $_data['ParentID'], $db_parent );

	// Проверим не запрещено ли записывать ЭСС у данного родителя
	//if ( mdl_permission( 'Structure_Save', $db_parent ) )
	//{
		// Установим "особые" поля
		$_data['Created'] = ( $_data['Created'] == 0 ) ? date('Y-m-d H:i:s') : $_data['Created'];

		// Установим специальные поля
		$_data ['Active'] = 1;

		// Выполним попытку сохранения записи в БД
		if( dbSimplify::save( 'samson\cms\cmsnav', $db_structure, $_data ) )
		{
			// Все прошло гуд
			return TRUE;
		}
	//}

	// Дошли сюда - были ошибки
	return FALSE;
}

/**
 * Удалить ЭСС и все его подчиненные ЭСС
 *
 * @param array $_data Данные из формы удаления
 */
function mdl_structure_delete( array $_data )
{
    $db_structure = NULL;
	// Получим ЭСС
	if( dbQuery( 'samson\cms\cmsnav')->StructureID($_data['StructureID'])->first($db_structure) )
	{
		// Проверим разрешено ли удалять данный элемент
		//if ( mdl_permission('Structure_Delete', $db_structure) )
		//{
            $children = NULL;
			// Выполним запрос на получение детей текущего элемента
			if ( _cmsnav()->ParentID( $db_structure->id )->exec($children) )
			{
				// Перебирем детей - углубимся в рекурсию
				foreach ( $children as $child ) mdl_structure_delete( $child );
			}

			// Удалить текущую запись
			$db_structure->Active = 0;

			// Запишем изменения в БД
			$db_structure->save();

            $db_connections = NULL;
			// Удалим связи структуры с материалом
			if ( _cmsnavmaterial()->StructureID( $db_structure->id )->exec($db_connections))
			{
				// Переберем полученные связи и удалим их из БД
				foreach ( $db_connections as $db_connection ) $db_connection->delete();
			}

			// Удалим связи структуры с доп. полями
			if ( _cmsnavfield()->StructureID( $db_structure->id )->exec($db_connections))
			{
				// Переберем полученные связи и удалим их из БД
				foreach ( $db_connections as $db_connection ) $db_connection->delete();
			}
		//}
	}
}

/**
 * Генератор HTML представления дерева
 * @return string HTML код дерева
 */
function mdl_structure_html_tree( Samson\ActiveRecord\idbRecord & $db_parent = NULL )
{
	// Попытаемся получить ЭНС по селектору
	$ne = isset( $db_parent ) ? cmsnav( $db_parent ) : CMSNav::$top;

	// Получим дерево навигации сайта
	return $ne->toHTML( $nav, $html, m()->path().__SAMSON_VIEW_PATH.'tree.element.tmpl.php' );
}

/**
 * Выполнить перемещение элемента структуры сайта по дереву
 * @param Structure $db_structure 	ЭСС для перемещения
 * @param number 	$direction		Направление перемещения( 1, -1 )
 */
function mdl_structure_priority( $db_structure, $direction = -1 )
{
	if( ifcmsnav( $db_structure, $db_structure, 'id' ) ) $db_structure->priority( $direction );
}

/**
* Получить список всех эсс для автозаполнения
* @param string $query Строка фильтр для списка эсс
* @return string Список всех эсс подходящим под запрос
*/
function mdl_structure_autocomplete( $query = '' )
{
	// Получим се эсс
 	$all_structures = _Structure()->find_all_by_Active(1);
	if ( dbSimplify::query( $all_structures, $all_structures, 1 ) )
	{
		// Шаблон для поиска структуры
		$structure_pattern = '/' . mb_strtolower( trim( $query ), 'UTF-8' ) . '/ui';

		// Результирующая строка
		$result = array();

		// Найдем подходящие должности
		foreach ( $all_structures as $db_structure )
		{
			// Соберем представление задачи
			$structure_str = $db_structure->Name;

			// Сверим подходит ли он к запросу
			if( preg_match( $structure_pattern, $structure_str ) )
			{
				$result[]=  array('name'=>$db_structure->Name, 'id'=>$db_structure->id );
			}
		}
		// Выдадим результаты
		return $result;
	}
	return '';
}

function mdl_structure_clone_parent_fields($structure_id)
{
	if(dbSimplify::parse( 'samson\cms\cmsnav', $structure_id, $db_structure ))
	{
		// Безопасно получим указатель на ЭСС в БД
		if( dbSimplify::parse( 'samson\cms\cmsnav', $db_structure->ParentID, $db_parent_structure ))
		{
			// Получим связи полей с ЭСС
			if ( dbSimplify::query(_structurefield()->find_all_by_StructureID_and_Active( $db_parent_structure->id, 1 ),$db_field_connections,true) )
			{
				foreach ($db_field_connections as $db_field_connection)
				{
					$db_structurefield = new StructureField();
					$db_structurefield->StructureID = $structure_id;
					$db_structurefield->FieldID = $db_field_connection->FieldID;
					$db_structurefield->Active = 1;
					$db_structurefield->save();
					//trace($db_structurefield->FieldID.' - '.$db_structurefield->StructureID);
				}
			}
		}
	}
}

/**
 * Сформировать коллекцию элементов навигации сайта(ЭНС) с сортировкой и указанием
 * количества материалов которые принадлежат каждому ЭНС.
 *
 * Если передать существующий ЭНС, то выборка по популярности будет осуществлена
 * по подчиненным ЭНС к переданному.
 *
 * @param CMSNav $db_parent Указатель на родительский ЭНС
 * @return array 	Коллекцию ЭНС в виде массива с количеством связанных материалов
 * 					хранимом в ключе массива: <code>КВО_МАТЕРИАЛОВ => ЭНС</code>
 */
function & mdl_structure_popular( CMSNav & $db_parent = NULL )
{
	// Коллекция самых популярных ЭНС
	$db_popular_navs = array();

	// Сформируем запрос для получения связей между ЭНС и материалом
	$db_query = dbQuery('CMSNavMaterial')
	->cond( 'Active', 1 )			// Выбираем только существующие записи
	->group_by('StructureID')		// Группируем записи по ИД ЭНС
	->join('Structure')				// Присоединим структуры
	->order_by('__Count','DESC');	// Отсортируем по количеству сгрупированных связей

	// Если передан родительский ЭНС, и у него есть подчиненные ЭНС
	if( isset( $db_parent ) && isset($db_parent['Children']) && sizeof($db_parent['Children']) )
	{
		// Коллекция идентификаторов подчиненных ЭНС
		$db_children_ids = array();

		// Заполним коллекцию идентификаторов подчиненных ЭНС
		foreach ( $db_parent->Children as $db_child ) $db_children_ids[] = $db_child->id;

		// Установим фильтр по ИД подчиненных ЭНС
		$db_query->cond( StructureID, $db_children_ids );
	}

	// Выполним запрос
	if( dbSimplify::query( $db_query->exec(), $db_navmaterials, true ) )
	{
		// Имя связи с ЭНС - локале не зависимое
		$loc_structure = '_'.locale().'structure';

		// Переберем самые популярные связи
		foreach ( $db_navmaterials as $db_navmaterial )
		{
			// Если в этом объекте есть связь указана ЭНС
			if( isset( $db_navmaterial->onetoone[ $loc_structure ]) )
			{
				// Добавим количество связей как ИД и ЭНС как значение
				$db_popular_navs[ $db_navmaterial->__Count ] = $db_navmaterial->onetoone[ $loc_structure ];
			}
		}
	}

	// Вернем то что получили
	return $db_popular_navs;
}
?>
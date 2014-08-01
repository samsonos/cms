<?php
use samson\activerecord\dbConditionArgument;

use samson\activerecord\dbConditionGroup;

use Samson\ActiveRecord\dbRelation;
use samson\cms\CMSNav;
use samson\cms\CMSMaterial;
use samson\cms\CMSMaterialField;
use samson\cms\CMSNavMaterial;
use samson\cms\CMSNavField;
use Samson\ActiveRecord\user;
use Samson\ActiveRecord\gallery;
use Samson\ActiveRecord\dbSimplify;
use samson\core\File;
use samson\pager\Pager;

/**
 * Сформировать представление формы материала
 * @param CMSMaterial 	$db_material 	Указатель  материала в БД
 * @param CMSNav	 	$db_structure 	Указатель ЭНС в БД
 * @return string HTML представление формы материала
 */
function mdl_material_form2( CMSMaterial & $db_material = NULL, CMSNav & $set_structure = NULL )
{
	$db_structures = array();
	
	$db_structure = null;
	
	// Получим пользователя
	$db_user = new user($db_material->UserID);
	
	$set_structure_id = '';
	// Если не передан ЭНС для материала
	if ( isset( $set_structure ) )	{$db_structures[] = $set_structure; $set_structure_id = $set_structure->id;}
	else $set_structure = new CMSNav( false );
	
	// Получил стили для редактора
	$css = '';
	
	// Проверим где может быть файл стилей для редактора
	if( file_exists('../css/cms.css')) $css = file_get_contents('../css/cms.css');
	else if( file_exists('../cms.css') ) $css = file_get_contents('../cms.css');	
	
	// Скомпилируем LESS - если он подключен
	if( class_exists('lessc')) try { $less = new lessc; $css = $less->compile( $css );	}
	catch( Exception $e){ e('Ошибка LESS: '.$e->getMessage()); }
	
	// Попытаемся получить ЭНС из материала
	if (isset($db_material->onetomany['_'.locale().'structurematerial'])) 
	{
		$db_structurematerials = $db_material->onetomany['_'.locale().'structurematerial'];
		foreach ($db_structurematerials as $db_structurematerial)
			if (($db_structurematerial->Active == 1) && ($db_structurematerial->StructureID != $set_structure_id)) 
				$db_structures[] = cmsnav( $db_structurematerial->StructureID, 'id');
	}
	// Иначе создадим пустышку для сохранения логики работы представления
	else 
	{
		$query = dbQuery('samson\cms\cmsnavmaterial')
			->cond('MaterialID', $db_material->id)
			->cond('Active', 1)
			->exec();
		
		if ( dbSimplify::query($query, $db_structurematerials, true) ) 
		{
			foreach ($db_structurematerials as $db_structurematerial)
				if ( $db_structurematerial->StructureID != $set_structure_id )$db_structures[] = cmsnav( $db_structurematerial->StructureID, 'id');
		}
		else $db_structures[] = $set_structure;
	}
	
	// Содержание закладки "Галлерея"
	$gallery_tab = '';
	// Содержание закладки "Основные дополнительные поля"
	$field_tab = '';
	// Содержание закладок для "Таблиц  Дополнительных полей"
	$field_tabs = '';	
	// Загловоки закладок для "Таблиц  Дополнительных полей"
	$field_tabs_name = '';	
	// Содержание закладки "Подчиненные материалы"
	$material_tabs = '';
	
	// Сформируем представление для закладки "Галлерея"
	if( !dbSimplify::query(_Gallery()->find_all_by_MaterialID_and_Active($db_material->id, 1), $gallery, TRUE )) $gallery = array();
	
	// Сформируем представление галлереи
	$gallery_tab = mout()
		->set( 'gallery', $gallery )
		->output('app/view/form/tab/gallery.php');
	
	$db_fields = array();

	// Получим все дополнительные поля для ЭНС которому принадлежит материал
	foreach ($db_structures as $db_structure)
	{
		$structure_fields_array = mdl_structure_fields( $db_structure );
		if (is_array($structure_fields_array))	$db_fields = array_merge( $db_fields,  $structure_fields_array);
	}

	// Получим значения всех дополнительных полей материала
	$db_materialfields = mdl_material_field_values( $db_material );
	
	// Если мы получили дополнительные поля для материала
	if( sizeof( $db_fields ) )
	{	
		// Счетчик закладок для дополнительных полей
		$tabs_count = 0;
		
		// Представление дополнительных полей
		$html_fields = '';
		
		// Переберем дополнительные поля для ЭНС
		foreach ( $db_fields as $db_field )
		{	
			// Если для материала еще не существует связи с дополнительным полем
			if( ! isset( $db_materialfields[ $db_field->id ] ) )
			{
				// Создадим новую связь материала с доп. полем
				$db_mf = new CMSMaterialfield();				
				$db_mf->FieldID = $db_field->id;				
				$db_mf->MaterialID = $db_material->id;				
				$db_mf->Active = 1;
				
				// Запишем связь в БД
				$db_mf->save();
			}			
			// Получим указатель на "полное" дополнительное поле
			else $db_mf = $db_materialfields[ $db_field->id ];		
			
			// Сформируем представление для значения доп. поля
			mout()->set( $db_mf )->set( $db_field );
			
			// Определим тип дополнительного поля
			switch( $db_field->Type )
			{			
				// Поле с ресурсом 	
				case 1: $html_field_value = mout()
					->set( 'imgage', File::isImage( $db_mf->Value ))
					->set( 'extension', pathinfo( $db_mf->Value, PATHINFO_EXTENSION ) )
					->output('app/view/form/field/link.php'); 
				break;
				
				// Поле с календарем
				case 3: $html_field_value = mout()->output('app/view/form/field/calendar.php'); 
				break;
				
				// Поле с выпадающим списком
				case 4: $html_field_value = mout()
					->set('select', html_form_select_from_list( $db_field->Value, $db_mf->Value ) )
					->output('app/view/form/field/select.php');
				break;
				
				// Поле таблица
				case 5:							
					// Получим HTML представление таблицы дополнительного поля
					$html_table = mdl_scmstable_table('FTable_'.$db_mf->id, 'app/view/table.php', 'FTable_'.$db_field->id);
					
					// Сформируем имя закладки
					$tab_name = 'table'.($tabs_count++).'-tab';					
					
					// Создадим описание закладки для перехода к ней
					$field_tabs_name .= '<li><div class="#'.$tab_name.'">'.$db_field->Name.'</div></li>';
					
					// Создадим содержимое дополнительной закладки в доп. полях
					$field_tabs .= mout()
						->set( 'table_data', $html_table )
						->set( 'tab-name', $tab_name )
						->output('app/view/form/tab/table.php');

					// Перейдем к следующему доп. полю
					continue 2;
									
				break;
				
				// Поле Материал
				case 6:
					$html_field_value = mout()->output('app/view/form/field/material.php');						
					break;
				// Поле Материал
				case 7:
					$html_field_value = $html_field_value = mout()->output('app/view/form/field/numeric.php');
					break;
				
				// Текстовое поле
				default: $html_field_value = mout()->output('app/view/form/field/text.php');
			}	
				
			// Сформируем полное представление доп. поля
			$html_fields .= mout()
				->set( 'field_value', $html_field_value )
				->set( $db_field )
				->output('app/view/form/field/index.vphp');
		}
		
		// Установим представление дополнительных полей
		$field_tab = mout()->set( 'html_fields', $html_fields )->output('app/view/form/tab/field.php');
	}
	
	//if (isset($db_material->ParentID))
	//{
		// Сформируем представление для закладки "Галлерея"
		//if( !dbQuery('CMSMaterial')->ParentID($db_material->id)->Active(1)->Draft(0)->exec($db_submaterial)) 
		$submaterial_html = mdl_material_submaterial_list($db_material->related());
		// Сформируем представление галлереи
		$material_tabs = mout()
		->set( 'submaterial_html', $submaterial_html)
		->output('app/view/form/tab/submaterial.php');
	//}

	// Установим параметры представления формы материала
	return mout()	
			->set( 'css', $css )
			->set( 'fixedName', utf8_limit_string( ''.$db_material->Name, 60 )) 				// Название материала в заголовоке
			->set( 'parent_select', html_db_form_select_options( 'samson\cms\cmsnav', $db_structures ) )	// Сгенерируем элемент формы для выбора ЭСС
			->set( 'submaterial_tab', $material_tabs ) 											// Установим закладку "Галлерея"
			->set( 'gallery_tab', $gallery_tab )
			->set( 'field_tab', $field_tab ) 													// Установим закладку "Дополнительные поля"
			->set( 'field_tabs', $field_tabs ) 													// Установим закладку "Таблицы доп. полей"			
			->set( 'field_tabs_name', $field_tabs_name ) 										// Установим закладку "Заголовки таблиц"
			->set( 'published',  ($db_material->Published)?'checked':'' ) 						// Галочка опубликован ли материал
			->set( $db_user ) 																	// Установим Пользователя
			->set( $db_structure ) 																// Установим ЭНС
			->set( $db_material )																// Установим Материал
			->output('app/view/form/tmpl.vphp'); 												// Установим представление
}

function mdl_material_submaterial_list($submaterials)
{
	$return = '';

	if ( is_array($submaterials) ) foreach ( $submaterials as $submaterial ) 
		$return .= mout()
		->set( 'submaterial', $submaterial )
		->output('app/view/form/tab/submaterial_row.php');
	return $return;

}

/**
 * Записать данные материала в БД
 * 
 * Если не передан указатель на материал в БД, то метод
 * создаст новый материал по переданным данным
 * 
 * @param array 		$data 			Данные материала
 * @param CMSMaterial 	$db_material 	Указатель на материал в БД	
 * @param mixed 		$status 		Специальная переменная для возврата ошибки
 * @return boolean Результат выполнения сохранения данных в БД
 */
function mdl_material_save2( array $data, CMSMaterial & $db_material = NULL, & $status = NULL )
{	
	//print_r($data);
	set_time_limit(1000);
	// Установим правильную дату создания материла
	$data['Created'] 	= ( $data['Created'] == '' ) ? date('Y-m-d H:i:s') : $data['Created'];		
	// Установим статус опубликованости материала
	$data['Published'] = ( isset($data['Published']) ) ? 1 : 0;	
	// Раз мы выполняем сохранение - материал существует
	$data['Active'] = 1;		
	
	// Сгенерируем SEO теги для материала 
	$meta = mdl_material_meta( $data['Content'] );
	
	// Если не передан метатег "Ключевые поля" - заполним его автоматически
	if ( $data['Keywords'] == '' ) $data['Keywords'] = $meta['keywords'];
	// Если не передан метатег "Описание" - заполним его автоматически
	if ( $data['Description'] == '' ) $data['Description'] = $meta['description'];	
	// Если не передан метатег "Заголовок" - заполним его автоматически
	if ( $data['Title'] == '' ) $data['Title'] = $data['Name'];
	
	// Выполним автоматическое сохранение/создание материала/черновика в БД
	if( dbSimplify::save( 'samson\cms\CMSMaterial', $db_material, $data ) )
	{			
		// Получим существующие связи материала с указанной структурой
		// Если связь между материалом и структурой существует
		if( dbSimplify::query( _cmsnavmaterial()->find_all_by_MaterialID_and_Active( $db_material->id, 1 ), $db_connections, true ) )
		{
			foreach ($db_connections as $db_connect)
			{
				// Если изменилась структура
				if( !in_array($db_connect->StructureID,  $data['StructureID']) )
				{
					// Удалим старые дополнительные поля материала
					if( dbSimplify::query( _cmsmaterialfield()->find_all_by_Active_and_MaterialID_and_StructureID ( 1, $db_connect->MaterialID, $db_connect->StructureID ), $fields, true ))
					{
						foreach ( $fields as $field )
						{
							$field->Active = 0;
							$field->save();
						}
					}
					$db_connect->Active = 0;
					$db_connect->save();
				}
			}
			
		}
		
		$field_array = array();
		
		if (dbQuery('field')->exec($db_fields)) foreach ($db_fields as $k=>$db_field) $field_array[$k] = $db_field->Type;

		
		for ($i = 0; $i < sizeof($data['StructureID']); $i++) 
		{
			$structure_id = $data['StructureID'][$i]; 
			if( !dbSimplify::query( _cmsnavmaterial()->find_all_by_MaterialID_and_StructureID_and_Active( $db_material->id, $structure_id, 1 ), $db_connection ) ) 
			{	
				$db_connection = new CMSNavMaterial();
				//trace($db_connection->id);
				// Запишем параметры связи в БД, на этом шаге мы однозначно имеем указатель на связь структуры с материалом
				$db_connection->MaterialID 	= $db_material->id;
				$db_connection->StructureID = $structure_id;
				$db_connection->Active=1;
				$db_connection->save();
			}
			//trace($db_connection->id);
			// Надем все дополнительные поля текущей структыры материала
			if( dbSimplify::query( _cmsnavfield()->find_all_by_StructureID_and_Active($db_connection->StructureID, 1), $db_structurefields , true ))
			{
				// Переберем дополнительные поля принадлежащие данной структуре
				foreach ($db_structurefields as $db_structurefield )
				{
					//trace( 'Обрабатываю доп. поле:'.$db_structurefield->FieldID);
					
					// Если переданны данные от формы по этому полю
					if ( isset( $data['Field_'.$db_structurefield->FieldID ] ) )
					{
						//trace($data['Field_' . $db_structurefield->FieldID ]);
						// Найдем существующую запись о связи материала и поля
						$existing_materialfields = _cmsmaterialfield()
						->cond('MaterialID', $db_material->id)
						->cond('FieldID', $db_structurefield->FieldID)
						->cond('Active', 1)
						->exec();
							
						// Если её не существует - создадим её
						if( ! dbSimplify::query( $existing_materialfields, $db_materialfields, true ) ) $db_materialfield = new cmsmaterialfield();
						else 
						{
							// Если нашли получим её
							$db_materialfield = $db_materialfields[0];
							
							// Если полуили больше одной записи - удалим остальные
							if (sizeof($db_materialfields) > 1) 
							{
								for ($j = 1; $j < sizeof($db_materialfields); $j++) 
								{
									$db_materialfields[$j]->Active = 0;
									$db_materialfields[$j]->save();
									//trace('del - '.$db_materialfields[$j]->id.' - '.$db_materialfields[$j]->Value);
								}
							}
						}
							
						//trace( 'Записываю доп. поле:'.$db_materialfield->id );
						
						// Запишем данные поля
						$db_materialfield->MaterialID = $db_material->id;
						$db_materialfield->FieldID = $db_structurefield->FieldID;
						/*trace($field_array[$db_structurefield->FieldID]);
						if (isset($field_array[$db_structurefield->FieldID])&&($field_array[$db_structurefield->FieldID]==7) )
						{
							trace($data[ 'Field_'.$db_structurefield->FieldID ]);
							$db_materialfield->numeric_value = $data[ 'Field_'.$db_structurefield->FieldID ];
						}
						else $db_materialfield->Value = $data[ 'Field_'.$db_structurefield->FieldID ];*/
						
						$db_materialfield->numeric_value = $data[ 'Field_'.$db_structurefield->FieldID ];
						$db_materialfield->Value = $data[ 'Field_'.$db_structurefield->FieldID ];
						
						$db_materialfield->Active = 1;	
						//trace($db_materialfield->id.' - '.$db_materialfield->Value );
						$db_materialfield->save();
					}
				}
			}
		}				
		
		// Сфорируем запрос на получение только черновиков для оригинала
		$draft_query = dbQuery('samson\cms\cmsmaterial')
			->cond( 'Active', 1 )// Ищем только существующие черновики
			->cond( 'UserID', auth()->user->id )// Ищем черновики для текущего пользователя
			->cond( 'Draft', $db_material->id ) // Ищем черновики для сохраняемого материала
			->cond( 'MaterialID', $db_material->id, dbRelation::NOT_EQUAL ) // "Голые" черновики пропускаем
		->exec(); // Выполним запрос		
		
		// Если мы нашли черновики для сохраняемого материала - переберем и удалим черновики	
		//if( dbSimplify::query( $draft_query, $md_drafts, true )) foreach ( $md_drafts as $md_draft )mdl_material_delete( $md_draft );			
		
		// Все прошло успешно
		return TRUE;
	}
	
	// Что-то пошло не так
	$status['error'] = 'Не удалось сохранить материал в базу данных';	
	return FALSE;	
}

/**
 * Сгенерировать HTML представление таблицы материалов
 * 
 * @param CMSNav 		$db_structure 	Указатель ЭСС в БД
 * @param SamsonPager 	$pager 			Указатель на класс для управления по страничным выводом
 * @return string HTML представление таблицы материалов
 */
function mdl_material_table2( CMSNav & $db_structure = NULL, Pager & $pager = NULL, $search = NULL )
{	
	return mdl_material_table3( $db_structure, $pager, $search );
	
	// Результат
	$result = '';	
	
	// Получим имя локализации
	$locale = locale();

	// Коллекция идентификаторов материалов по ЭНС
	$db_material_ids = array();
	
	// Получим параметры сортировки таблицы материалов
	$order = isset($_SESSION['Material_Table_Order_By'])?$_SESSION['Material_Table_Order_By']:'Created';
	$order_direction = isset($_SESSION['Material_Table_Order_Direction'])?$_SESSION['Material_Table_Order_Direction']:'DESC';
	

	// Сформируем запрос на получение материалов из БД
	$db_query = dbQuery('samson\cms\cmsmaterial')
	->own_order_by($order, $order_direction)			// Отсортируем записи по дате создания
	->join( $locale.'structurematerial' )	// Подключим связь ЭНС с материалом
	->join( $locale.'structure' )			// Подключим связанную ЭНС
	->cond( 'Active', 1 )
	->cond( 'Draft', 0 )
	->cond( $locale.'structurematerial_Active', 1)
	->join( 'user' );						// Подключим связанного пользователя	
	
	// Если передана ЭНС - установим фильтр по ней
	if( isset( $db_structure ) )
	{
		if( dbQuery('samson\cms\cmsmaterial')
		->join( $locale.'structurematerial' )
		->cond( $locale.'structurematerial_StructureID', $db_structure->id )
		->own_group_by('MaterialID')->exec($db_materials))
		{
			// Соберем идентификаторы в коллекцию
			foreach ($db_materials as $m) $db_material_ids[] = $m->id;			
		}
		
		// Добавим материалы которые нам подходят
		$db_query->MaterialID( $db_material_ids );
	}	
	$search="фы";
	// Если задан поисковый запрос
	if ( isset( $search ) )
	{
		// Создадим список атребутов для поиска
		$search_attributes = array('Name', 'Url','Content', $locale.'structure_Name');		
		
		// Создадим группу условий
		$scg = new dbConditionGroup('or');
		
		$db_query = $db_query->or_();
		
		// Соберем все условия для текстового поиска соответствий
		foreach ($search_attributes as $item)// $db_query->cond( $item, '%'.$search.'%', ' like ' );			
			$scg->arguments[] = new dbConditionArgument( $item, '%'.$search.'%', ' like ');		
		
		// Добавим группу условий в запрос
		$db_query->cond( $scg );
		
		//trace($db_query);
	}
	// Если передан класс для работы с по-страничным выводом
	else if( isset( $pager )  )
	{
		// Скопируем запрос
		$count_query = clone $db_query;
		
		// Выполним запрос на расчет количества материалов	
		// Рассчитать параметры по-страничного вывода
		$pager->update( $count_query->count() );	
		
		// Ограничим количество выводимых материалов если нужно
		$db_query->own_limit( $pager->start, $pager->end );	
	}	
	
	// Если передана ЭНС - установим фильтр по ней // Установим необходимые материалы
	//if( isset( $db_structure ) ) $db_query->MaterialID( $db_material_ids );
	
	$GLOBALS['show_sql'] = true;
	// Выполним запрос к БД для получения материалов
	$db_materials = $db_query->exec();	
	unset($GLOBALS['show_sql']);
	

	// Сформируем запрос на получение черновиков материалов из БД
	$db_query = dbQuery('samson\cms\cmsmaterial')
	->cond( 'Active', 1 )						// Выберем только существующие записи
	->cond( 'Draft', 0, dbRelation::NOT_EQUAL ) // Выберем только существующие записи
	->own_order_by('Created', 'DESC')				// Отсортируем записи по дате создания
	->join( $locale.'structurematerial' )		// Подключим связь ЭНС с материалом
	->join( $locale.'structure' )				// Подключим связанную ЭНС
	->join( 'user' );							// Подключим связанного пользователя
	
	// Если передана ЭНС - установим фильтр по ней
	if( isset( $db_structure ) ) $db_query->cond( $locale.'structurematerial_StructureID', $db_structure->id );	
	
	// Получим остальные материалы
	$db_all_materials = $db_query->exec();
	
	// Коллекция черновиков материалов
	$db_drafts = array();	
	
	// Коллекция "голых" черновиков материалов
	$db_empty_drafts = array();

	// Переберем все черновики и создадим ассоциативный массив
	foreach ( $db_all_materials as $db_material ) 
	{
		// Если это голый черновик
		if( $db_material->Draft == $db_material->id ) $db_empty_drafts[] = $db_material;
		// Это черновик
		else $db_drafts[ $db_material->Draft ] = $db_material;
	}		
	
	// Если материалов нет - выведем пустую строку
	if( ! sizeof( $db_materials ) )
	{ 
		// Если нет материалов по ЭНС
		if( isset($db_structure) ) return mout()->output('app/view/table/row/notfound.php');
		// Просто нет ни одного материала
		else return m()->output('app/view/table/row/empty.php');
	}	

	// Индекс материала
	$mat_idx = isset( $pager) ? $pager->start + 1 : 1;
	
	// Переберем все полученные из БД черновики материалов
	foreach ( $db_empty_drafts as $db_material )
	{
		// Установим черновик в контекст представления и укажем что он без оригинала
		mout()->set( $db_material )->set('is_draft','1');
	
		// Установим автора в контекст представления если он задан
		if( isset($db_material->onetoone['_user']) ) mout()->set( $db_material->onetoone['_user'] );
		
		// Список связаных структур к материалу
		$join_structures = array();
		
		// Если существуют связаные структуры
		if( isset($db_material->onetomany['_'.$locale.'structure']) ) $join_structures = $db_material->onetomany['_'.$locale.'structure'];
		
		// Передадим связанные стуктуры в представление
		mout()->set( 'join_structures', $join_structures );	
		// Сгенерируем представление строки таблицы материалов
		$result .= mout()->set($pager)
		->set( 'parent_cmsnav', isset($db_structure) ? $db_structure->id : '0' )
		->set( 'idx', $mat_idx++ ) // Установим номер строки материала
		->output('app/view/table/row/index.vphp'); // Cформируем представление
		//break;
	}	

	// Переберем все полученные из БД материалы
	foreach ( $db_materials as $db_material )  
	{		
		// Установим материал в контекст представления
		mout()->set( $db_material );		
		
		// Установим автора в контекст представления если он задан
		if( isset($db_material->onetoone['_user']) ) mout()->set( $db_material->onetoone['_user'] );	
		
		// Список связаных структур к материалу
		$join_structures = array();	
		
		// Если существуют связаные структуры
		if( isset($db_material->onetomany['_'.$locale.'structure']) ) $join_structures = $db_material->onetomany['_'.$locale.'structure'];
		
		// Передадим связанные стуктуры в представление
		mout()->set( 'join_structures', $join_structures );
	
		// Если для материала существует черновик - установим
		if( isset($db_drafts[ $db_material->id ]) ) 
		{
			// Получим указатель на черновик
			$db_draft = $db_drafts[ $db_material->id ];
			
			// Установим данные черновика в представление
			mout()
				->set( 'draft_modyfied', date( 'H:i:s d.m.Y', strtotime( $db_draft->Modyfied )) ) // Сформируем дату создания черновика				
				->set( $db_draft->toView('draft_') ); // Установим черновика в представление
		}
		
		// Сгенерируем представление строки таблицы материалов
		$result .= mout()->set($pager)
			->set( 'parent_cmsnav', isset($db_structure) ? $db_structure->id : '0' )
			->set( 'idx', $mat_idx++ ) // Установим номер строки материала
			->output('app/view/table/row/index.vphp'); // Cформируем представление
	}	
		
	// Сформируем полностью таблицу материалов
	return mout()->set( 'material_rows', $result )->output('app/view/table/tmpl.vphp');
}

/**
 * Получить все значения дополнительных полей материала
 *
 * @param CMSMaterial $db_material Указатель на материал
 * @return array Коллекцию полей к привязанных к материалу
 */
function mdl_material_field_values( CMSMaterial & $db_material )
{
	// Коллекция значений полей материала
	$db_field_values = array();	

	// Получим значение полей материала
	$db_material_connection = _cmsmaterialfield()->find_all_by_MaterialID_and_Active_join_Field( $db_material->id, 1 );
	//trace($db_material_connection);
	// Преобразуем коллекцию значений полей для материала в коллекцию с ключами
	for ($i = 0; $i < sizeof($db_material_connection); $i++)
	{	
		// Запишем доп. поле в результат по ИД доп. поля
		$db_field_values[ $db_material_connection[ $i ]->FieldID ] = $db_material_connection[ $i ];
	}	

	// Вернем коллекцию значений полей материала
	return $db_field_values;
}

/**
 * Удалить материал сайта
 * 
 * @param mixed $db_material Указатель на материал БД для удаления
 * @return boolean Результат выполнения удаления материала
 */
function mdl_material_delete( $material_id )
{	

	// Безопасно получим "Материал"
	if( _cmsmaterial()->Active(1)->MaterialID($material_id)->first( $db_material ))
	{	

		// Проверим права пользователя на удаление материала
		//if ( ! mdl_permission('Material_Delete', $db_material, $error) ) { $status = 'permission'; return FALSE; }

		// Удалим сам материал
		$db_material->Active = 0;
		$db_material->save();	
		
		// Удалим связь структуры с материалом
		if( dbSimplify::query( _cmsnavmaterial()->find_all_by_Active_and_MaterialID( 1, $db_material->id ), $db_connections, TRUE ) )
		{
			foreach ( $db_connections as $db_connection )
			{
				$db_connection->Active = 0;
				$db_connection->save();
			}
		}	
		
		// Удалим все активные черновики для материала
		if( dbSimplify::query(_CMSMaterial()->find_all_by_Active_and_Draftmaterial ( 1, $db_material->id ), $drafts, TRUE ))
		{
			foreach ( $drafts as $draft )
			{
				$draft->Active = 0;
				$draft->save();
			}
		}
			
		// Удалим все дополнительные поля материала
		if( dbSimplify::query(_CMSMaterialfield()->find_all_by_Active_and_MaterialID ( 1, $db_material->id ), $fields, TRUE) )
		{
			foreach ( $fields as $field )
			{
				$field->Active = 0;
				$field->save();
			}
		}
		
		//Очистим кеш
		//cms()->cache_clear();
		
		// Все прошло успешно
		return TRUE;
	}

	return FALSE;
}


/**
 * Опубликовать/Снять публикацию материала сайта
 * @param object $db_material Элемент для публикации
 */
function mdl_material_publish( $material_id, & $returnError = '' )
{
	// Безопасно получим "Материал"
	if( dbSimplify::parse('cmsmaterial', $material_id, $db_material))
	{
		// Проверим права пользователя на действие
		if ( ! mdl_permission('Material_Publish', $db_material, $error) ) 
		{
			// Зафиксируем описание ошибки
			$returnError = 'Не достаточно прав дял публикации материала!';
			
			// Действие не выполнено
			return FALSE;
		}
		
		// Если материал опубликован то снимем публикацию или наоборот
		$db_material->Published = $db_material->Published ? 0 : 1;

		// Запишем изменения в БД
		$db_material->save();

		// Очистим кеш
		//cms()->cache_clear();
		
		// Действие не выполнено
		return TRUE;
	}
	
	// Зафиксируем описание ошибки
	$returnError = 'Требуемый материал(#'.$material_id.') не найден!';
	
	// Ошибка выполнения действия
	return FALSE;
}

/**
 * Получить список всех материалов для автозаполнения
 * @param string $query Строка фильтр для списка сотрудников
 * @return string Список всех должностей с разделителем
 */
function mdl_material_autocomplete( $query = '' )
{
	//trace($query);
	//$db_materials = _Material()->find_all_by_Active_and_Draft( 1, 0 );
	$db_materials = dbQuery('samson\cms\cmsmaterial')->cond('Active', 1)->cond('Draft', 0)->exec();
	//$db_user_drafts = _Material()->find_all_by_Active_and_Draft_and_Draftmaterial_UserID( 1, 1, 0, auth()->user->id );
	$db_user_drafts = array();
	$all_materials = array_merge($db_materials, $db_user_drafts);

	// Шаблон для поиска материала
	$material_pattern = '/' . mb_strtolower( trim( $query ), 'UTF-8' ) . '/ui';

	// Результирующая строка
	$result = array();

	// Найдем подходящие должности
	foreach ( $all_materials as $db_material )
	{
		// Соберем представление материала
		$material_str = $db_material->Name;

		// Сверим подходит ли он к запросу
		if( preg_match( $material_pattern, $material_str ) )
		{
			$result[]=  array('name'=>$db_material->Name, 'id'=>$db_material->id, 'url'=>$db_material->Url );
		}
	}
	// Добавим пустой материал
	$result[] = array('name'=>'Материал не выбран', 'id'=>0);
	// Выдадим результаты
	return $result;
}

function mdl_material_meta( $text )
{
	if ( $text != '' )
	{
		// Уберем все скрипты из текста		
		$text = preg_replace('#<script[^>]*>.*?</script>#is', '', $text);
		// Нормализуем полученный текст
		$text=trim(stripslashes(preg_replace('/[\r\n\t]/i', ' ', strip_tags($text))));
		// Формируем описание из текста, макс.200 за до первого знака пунктуации
		$description_length = 200;
		$description = mb_substr($text, 0, $description_length, 'UTF-8');
		$simbol = array('!', '.', '?'); $index = 0;
		for ($i = 0; $i < sizeof($simbol); $i++) 
		{
			$current_index = mb_strrpos($description, $simbol[$i], 0, 'UTF-8');
			if ( $current_index > $index ) $index = $current_index;
		}
		if ( $index > 0	) $description = mb_substr($description, 0, $index+1, 'UTF-8');	
		$meta['description'] = $description;
		//trace($description); trace('');trace('');
		// Удаляем все знаки препинаний и пунктуации
		$text = str_replace('nbsp', '', $text);
		$text = preg_replace('/[^a-zA-Zа-яА-ЯЇїіІ]/u'," ",$text);

		// Разделим текст на слова
		$text = explode(' ', $text);
		$words = array();
		// Уберем все слова короче 4 символов 
		for ($i = 0; $i < sizeof($text); $i++) 
		{
			if ( mb_strlen($text[$i], 'UTF-8') > 3 ) $words[] = mb_strtolower( $text[$i], 'UTF-8' );	
		}
			
		$text = $words;		

		$arr1=array(); $arr2=array(); $arr3=array();
		$count = sizeof($text);
		// Строим массив вхождений похожих слов
		$temp_text = $text;
		for ($i = 0; $i < sizeof($text)-1; $i++) 
		{
			$word = $text[$i];
			if ($word != '-')
			{
				$arr1[$i] = array();
				$arr1[$i][] = $word;
				for ($j = $i+1; $j < sizeof($text); $j++) 
				{					
					if ( $text[$j] != '-' )
					{
						// Найдем длину слова для Unicode
						$len_word = mb_strlen($word, 'UTF-8');
						// Обрежем последний символ для Unicode
						$sub_word = mb_substr($word, 0, $len_word-1, 'UTF-8');
						// Найдем длину слова для Unicode
						$len_text = mb_strlen($text[$j], 'UTF-8');
						// Обрежем последний символ для Unicode
						$sub_text = mb_substr($text[$j], 0, $len_text-1, 'UTF-8');	
						// Проверим похожесть слов
						if ( ( mb_strpos( $text[$j], $sub_word, 0, 'UTF-8') === 0 ) || ( mb_strpos( $word, $sub_text, 0, 'UTF-8') === 0) )
						{
							$arr1[$i][] = $text[$j];
							$text[$j] = '-';
						}
					}
				}
			}
		}		
		$text = $temp_text;

		// Посчитаем частоту вхождения слов	
		$t_arr1 = $arr1;
		$arr1 = array();
		foreach ($t_arr1 as $v)
		{
			$short_key = $v[0];
			foreach ( $v as $value)
			{		
				if ( mb_strlen($value, 'UTF-8') < mb_strlen($short_key, 'UTF-8') ) $short_key = $value;
			}
			$arr1[$short_key] = sizeof($v);
		}
		unset($t_arr1);
		arsort($arr1);
		$i = 0;
		// Строим массив фраз состоящих из двух слов отсортированный по частоте вложений в тексте
		for($i=0; $i<$count-1; $i++) {
			$word=$text[$i].' '.$text[$i+1];
			if(isset($arr2[$word]))$arr2[$word]++; else $arr2[$word]=1;
		}
		arsort($arr2);
		
		// Строим массив фраз состоящих из трех слов отсортированный по частоте вложений в тексте
		for($i=0; $i<$count-2; $i++) {
			$word=$text[$i].' '.$text[$i+1].' '.$text[$i+2];
			if(isset($arr3[$word]))$arr3[$word]++; else $arr3[$word]=1;
		}
		arsort($arr3);	

		$data=array(); 
		// Выбираем 15 первых слов с максимальной частотой вложений
		$i=0;
		foreach($arr1 as $word=>$count) 
		{
			$data[$word]=$count;
			if ( ( $i ++== 8 ) || ( $count < 4 ) )break;
		}
		// Выбираем 8 первых фраз состоящих из двух слов с максимальной частотой вложений
		$i=0;
		foreach($arr2 as $word=>$count) 
		{
			$data[$word]=$count;
			if( ( $i ++== 7 ) || ( $count < 2 ) )break;
		}
		// Выбираем 4 первых фраз состоящих из трех слов с максимальной частотой вложений
		$i=0;
		foreach($arr3 as $word=>$count) 
		{
			$data[$word]=$count;
			if( ( $i++==4 ) || ( $count < 2 ) ) break;
		}	
		
		arsort($data); $text='';
		
		// Переводим массив фраз в текст, опять таки с учетом частот вложений
		foreach($data as $word=>$count) $text.=', '.$word; $text=substr($text, 1);
		// Ограничим длину keywords 250 символами
		$keywords_length = 250; 
		$text = mb_substr($text, 0, $keywords_length+1, 'UTF-8');
		
		$meta['keywords'] = $text;
		//trace($text);

		// Возвращаем полученный результат
		return $meta;
	}
}
?>
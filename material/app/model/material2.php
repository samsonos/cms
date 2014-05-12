<?php
use samson\cms\material\FieldTable;
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
use samson\cms\input\Field;

/**
 * Сформировать представление формы материала
 * @param CMSMaterial 	$db_material 	Указатель  материала в БД
 * @param CMSNav	 	$db_structure 	Указатель ЭНС в БД
 * @return string HTML представление формы материала
 */
function mdl_material_form3( CMSMaterial & $db_material = NULL, CMSNav & $set_structure = NULL )
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
	
	$db_fields = array();
	
	// Получим все дополнительные поля для ЭНС которому принадлежит материал
	foreach ($db_structures as $db_structure)
	{
		$structure_fields_array = mdl_structure_fields( $db_structure );
		if (is_array($structure_fields_array))	$db_fields = array_merge( $db_fields,  $structure_fields_array);
	}
	
	// Получим значения всех дополнительных полей материала
	$db_materialfields = mdl_material_field_values( $db_material );
	//trace(sizeof( $db_fields ));
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
				// Поле таблица
				case 5:							
					// Получим HTML представление таблицы дополнительного поля
					/*$html_table = mdl_scmstable_table('FTable_'.$db_mf->id, 'app/view/table.php', 'FTable_'.$db_field->id);
					
					// Сформируем имя закладки
					$tab_name = 'table'.($tabs_count++).'-tab';					
					
					// Создадим описание закладки для перехода к ней
					$field_tabs_name .= '<li><div class="#'.$tab_name.'">'.$db_field->Name.'</div></li>';
					
					// Создадим содержимое дополнительной закладки в доп. полях
					$field_tabs .= mout()
						->set( 'table_data', $html_table )
						->set( 'tab-name', $tab_name )
						->output('app/view/form/tab/table.php');*/

					// Перейдем к следующему доп. полю
					//continue 2;
									
				break;
				
				// Поле таблица
				case 8:
					// Получим HTML представление таблицы дополнительного поля
					$html_wysiwig = '';
						
					// Сформируем имя закладки
					$tab_name = 'wysiwyg'.($tabs_count++).'-tab';
						
					// Создадим описание закладки для перехода к ней
					$field_tabs_name .= '<li><div class="#'.$tab_name.'">'.$db_field->Name.'</div></li>';
						
					// Создадим содержимое дополнительной закладки в доп. полях
					$wysiwyg_tabs = '';
					
					$input = Field::fromObject( $db_mf, 'Value', 'Wysiwyg' );
					//trace($input->toView());
					$html_wysiwig = $input->toView();
					// Перейдем к следующему доп. полю
					
					$field_tabs .= mout()
						->set( 'wysiwyg_data', $html_wysiwig['Value_field'] )
						->set( 'tab-name', $tab_name )
						->output('app/view/form2/tab/wysiwyg.php');
					//continue 2;
						
					break;
				// Текстовое поле
				default: break;
			}	
				
			// Сформируем полное представление доп. поля
			/*$html_fields .= mout()
				->set( 'field_value', $html_field_value )
				->set( $db_field )
				->output('app/view/form/field/index.php');	*/
		}
		
		// Установим представление дополнительных полей
		//$field_tab = mout()->set( 'html_fields', $html_fields )->output('app/view/form/tab/field.php');
	}

	// Сформируем представление для закладки "Галлерея"
	if( !dbSimplify::query(_Gallery()->find_all_by_MaterialID_and_Active($db_material->id, 1), $gallery, TRUE )) $gallery = array();

	// Сформируем представление галлереи
	$gallery_tab = mout()
	->set( 'gallery', $gallery )
	->output('app/view/form/tab/gallery.php');
	
	$ids = array();
	foreach ( $db_structures as $db_structure ) $ids[] = $db_structure->id;
	
	$fieldtable = new FieldTable( $db_material->id, $ids );	

	// Установим представление дополнительных полей
	$field_tab = $fieldtable->render();

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
	->output('form2/tmpl'); 												// Установим представление
}

function mdl_material_table3( CMSNav & $db_structure = NULL, Pager & $pager = NULL, $search = NULL )
{
	//$GLOBALS['show_sql'] = true;
	$db_materials = cmsquery()->join('samson\cms\cmsnav')->handler( 'mdl_material_pager', $pager, $search )->published();

	// Индекс материала
	$mat_idx = isset( $pager) ? $pager->start + 1 : 1;

	$result = '';
	foreach ( $db_materials as $db_material )
	{
		// Установим автора в контекст представления если он задан
		if( isset($db_material->user) ) m()->set( $db_material->user );

		// Если существуют связаные структуры
		if( isset($db_material->onetomany['_structure']) ) m()->set('join_structures', $db_material->onetomany['_structure']);

		// Если для материала существует черновик - установим
		/*if( isset($db_drafts[ $db_material->id ]) )
		 {
		// Получим указатель на черновик
		$db_draft = $db_drafts[ $db_material->id ];

		// Установим данные черновика в представление
		mout()
		->set( 'draft_modyfied', date( 'H:i:s d.m.Y', strtotime( $db_draft->Modyfied )) ) // Сформируем дату создания черновика
		->set( $db_draft->toView('draft_') ); // Установим черновика в представление
		}*/

		// Сгенерируем представление строки таблицы материалов
		$result .= m()
		->set( $db_material )
		->set($pager)
		->set( 'parent_cmsnav', isset($db_structure) ? $db_structure->id : '0' )
		->set( 'idx', $mat_idx++ ) // Установим номер строки материала
		->output('table/row/index'); // Cформируем представление
	}

	// Сформируем полностью таблицу материалов
	return m()->set( 'material_rows', $result )->output('table/tmpl');
}

function mdl_material_pager( & $query, $pager = null,  $search= null)
{
	// Сортировщик по цене\наименованию
	//if( isset($price_or_name) ) $query->order_by( $price_or_name ? 'material.Цена':'material.Name', 'ASC' );

	// Фильтр по полу костюма
	//if( isset($gender) ) $query->cond( 'material.Пол', $gender );

	// Если задан поисковый запрос
	if ( isset( $search ) )
	{
		// Создадим список атребутов для поиска
		$search_attributes = array('Name', 'Url','Content', 'structure_Name');

		// Создадим группу условий
		$scg = new dbConditionGroup('or');

		// Соберем все условия для текстового поиска соответствий
		foreach ($search_attributes as $item) $scg->arguments[] = new dbConditionArgument( $item, '%'.$search.'%', ' like ');

		// Добавим группу условий в запрос
		$query->cond( $scg );

		//trace($db_query);
	}



	// Connect module pager
	//$pager = new samson\pager\Pager( $page, 8, 'catalog/'.$category->Url, $count_query->count() );
	if (isset($pager)) {
		// Clone query for count request
		$count_query = clone $query;
		//trace($count_query->innerCount());
		//$GLOBALS['show_sql'] = true;
		$pager->update($count_query->innerCount());
		//unset($GLOBALS['show_sql']);
		// Set originl query limit
		//trace($pager);
		$query->own_limit( $pager->start, $pager->end );
	}



	// Pass pager to view
	//m()->set( $pager );
}

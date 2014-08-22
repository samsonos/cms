<?php 
use samson\cms\CMSNav;
use samson\cms\CMSMaterial;
use Samson\ActiveRecord\dbSimplify;
use samson\core\File;

// TODO: Совместное редактирование материала, фиксация отметки времени записи черновика материала и проверка этой
//		 отметки при открытии, и сверка с частотой записи черновика, обновление её при записи черновика.
// TODO: Придумать механизм стилей в CMS что бы cms понимали стили сайта
// TODO: Перенести папку upload в корень сайта - тогда при сворачивании все картинки будут на месте


/**
 * Контроллер для обновления модуля
 * @return Ambigous <string, NULL>
 */
function material_update_handler( $structure_id = NULL, $page = NULL )
{
	// Попытаемся найти ЭНС
	$db_nav = cmsnav( $structure_id, 'id' );
	
	// Создадим пейджиризатор
	$pager = new samson\pager\Pager( $page, 20, 'material/'.$structure_id );
	
	// Вернем содежимое таблицы
	return mdl_material_table2( $db_nav, $pager ); 
}

/**
 * Ассинхронный контроллер для удаления материала
 */
function material_ajax_delete( $structure_id = NULL, $material_id = NULL, $page = NULL )
{	
	udbc()->ajax_action( 'mdl_material_delete', array( $material_id ), 'material_update_handler', array( $structure_id, $page ) );	
}

/**
 * Ассинхронный контроллер для публикации материала
 */
function material_ajax_publish( $structure_id = NULL, $material_id = NULL, $page = NULL )
{
	udbc()->ajax_action( 'mdl_material_publish', array( $material_id ), 'material_update_handler', array( $structure_id, $page ) );
}

function material_form2( $material_id = NULL, $structure_id = NULL )
{
	/*
	
	$db_material = new CMSMaterial( $material_id );
	
	$p = new samson\forms\Parser( m()->path().'app/view/form2/tmpl.vphp' );
	
	
	
	$f = new samson\cms\material\MaterialForm( $db_material );
	
	// Установим параметры представления формы материала
	m()	
	->html( $f->render() ) // Установим представление
	->title( $db_material->Name.' - Управление материалом');	// Установим заголовок
	*/
	// Если мы не получили материал
	if( NULL == ($db_material = CMSMaterial::get( array('MaterialID', $material_id))))
	{
		// Если был передан идентификатор - но материал не найден - выходим
		if( $material_id != 0 ) return material();
		// Если идентификатор материал не передан - создадим новый материал
		else
		{
			// Установим для меню что это новый материал
			m('menu')->set('new_material','1');
				
			// Создадим черновик для нового материала
			$db_material = new CMSMaterial();
				
			// Стандартное имя
			$db_material->Name = 'Новый материал';
	
			// Сделаем его "голым" черновиком
			$db_material->Draft = $db_material->id;
	
			// Установим ему пользователя-создателя
			$db_material->UserID = auth()->user->id;
				
			// Установим дату создания
			$db_material->Created = date('Y-m-d H:i:s');
			$db_material->Active = 1;
			$db_material->save();
		}
	}
	// Иначе выберем первый материал
	else $db_material = $db_material[0];
	
	// Если ЭНС с которым связан материал не найден
	if( ! ifcmsnav( $structure_id, $db_structure, 'id') )
	{
		// Попытаемся получить ЭНС из материала
		//if (isset($db_material->onetoone['_'.locale().'structurematerial'])) $db_structure = cmsnav( $db_material->onetoone['_'.locale().'structurematerial']->StructureID, 'id');
		// Иначе создадим пустышку для сохранения логики работы представления
		//else $db_structure = new CMSNav( false );
	}
	
	
	//$f = new MaterialForm( $db_material );
	
	// Установим параметры представления формы материала
	m()
	->html( mdl_material_form3( $db_material, $db_structure ) ) // Установим представление
	//->html( $f->render() ) // Установим представление
	->title( $db_material->Name.' - Управление материалом');	// Установим заголовок
	
	// Установим параметры представления для главного меню
	m('menu')->set( 'material', $db_material->Url );//->set( $db_structure );
}

/**
 * Контроллер для формы материала
 * 
 * @param mixed $material_id Идентификатор материала в БД
 */
function material_form_new( $material_id = NULL, $structure_id = NULL )
{			
	//$db_material = new CMSMaterial( $material_id );		
	
	// Если мы не получили материал
	if( NULL == ($db_material = CMSMaterial::get( array('MaterialID', $material_id))))	
	{
		// Если был передан идентификатор - но материал не найден - выходим
		if( $material_id != 0 ) return material();
		// Если идентификатор материал не передан - создадим новый материал
		else
		{
			// Установим для меню что это новый материал
			m('menu')->set('new_material','1');
			
			// Создадим черновик для нового материала
			$db_material = new CMSMaterial();
			
			// Стандартное имя
			$db_material->Name = 'Новый материал';
				
			// Сделаем его "голым" черновиком
			$db_material->Draft = $db_material->id;
				
			// Установим ему пользователя-создателя
			$db_material->UserID = auth()->user->id;
			
			// Установим дату создания
			$db_material->Created = date('Y-m-d H:i:s');
			$db_material->Active = 1;
			$db_material->save();
		}
	}
	// Иначе выберем первый материал
	else $db_material = $db_material[0];
	
	// Если ЭНС с которым связан материал не найден
	if( ! ifcmsnav( $structure_id, $db_structure, 'id') )
	{		
		// Попытаемся получить ЭНС из материала
		//if (isset($db_material->onetoone['_'.locale().'structurematerial'])) $db_structure = cmsnav( $db_material->onetoone['_'.locale().'structurematerial']->StructureID, 'id');
		// Иначе создадим пустышку для сохранения логики работы представления
		//else $db_structure = new CMSNav( false );
	}	
	
	
	//$f = new MaterialForm( $db_material );
	
	// Установим параметры представления формы материала
	m()	
		->html( mdl_material_form2( $db_material, $db_structure ) ) // Установим представление
		->title( $db_material->Name.' - Управление материалом');	// Установим заголовок	
	
	// Установим параметры представления для главного меню
	m('menu')
		->set( 'material', $db_material->Url )	
		->set( 'submenu', m()->output('sub_menu') );
}

/** Ассинхронный обработчик сохранения материала в БД */
function material_ajax_save()
{	
	// АЯКС
	s()->async(TRUE);

	// Объект для возвращения клиенту
	$status = array();
	
	// Проверим имя материала
	if ( ! isset( $_POST['Name']{0}) ) $status['status'] = 'Введите имя материала';	
	// Проверим URL материала
	if ( ! isset( $_POST['Url']{0}) ) $status['status'] = 'Введите URL материала';
		
	// Если сохраняется черновик, найдем его оригинал для сохранения
	// иначе получим идентификатор черновика
	$material_id = $_POST['Draft'] ? $_POST['Draft'] : $_POST['MaterialID'];
	
	// Сформируем универсальный запрос на получение оригинала материала
	$material_query = dbQuery('samson\cms\cmsmaterial')
		->cond( 'MaterialID', $material_id ) // Установим идентификатор материала
		->cond( 'Active', 1 ); // Ищем только существующие оригиналы

	
	// Если мы записываем черновик и мы не нашли его оригинал - выведем ошибку
	if ( ! $material_query->first($db_material) && ( $_POST['Draft'])  )
	{
		// Для любого Черновика должен быть Оригинал!!!!!!!
		$status['status'] = 'Ошибка сохранения черновика - не найден оригинал материала для черновика!';
	}		
	
	// Если не было ошибок - выполним сохранение материала
	if( ! isset( $status['status'] ) )
	{	
		// Мы всегда записываем оригинал материала
		$_POST['Draft'] = 0;	
		
		// Сохраним материал
		mdl_material_save2( $_POST, $db_material, $status );

		// Выведем форму материала
		$status['form'] = mdl_material_form2( $db_material );
	}
	
	// Вернем ответ для клиента
	echo json_encode( $status );
}

/**
 * Сохранить черновик материала
 */
function material_ajax_draft_save()
{
	// Ассинхронный вывод
	s()->async(TRUE);	
	
	// Сформируем универсальный запрос на получение черновика материала
	$query = _cmsmaterial()
	->cond( 'UserID', auth()->user->id )// Ищем черновики для текущего пользователя
	->cond( 'Active', 1 ); // Ищем только существующие черновики
	
	// Если сохраняется оригинал, найдем его черновик для сохранения
	// иначе сохраняется черновик, найдем его для сохранения
	$condition = $_POST['Draft'] ? 'MaterialID' : 'Draft';
	
	if ($_POST['Draft']) $query->cond( 'MaterialID', $_POST['Draft'] );
	else $query->cond( 'Draft', $_POST['MaterialID'] );
	
	
	 // Выполним запрос
	
	// Если черновик материала не найден 
	if ( ! $query->first($db_material) )
	{
		// Установим ссылку на оригинал материала
		$_POST['Draft'] = $_POST['MaterialID'];
		
		// Если сохраняется черновик
		/*if ( $_POST['Draft'] ) 
		{		
			// Для любого Черновика должна быть запись в базе!!!!!!!
			$status['status'] = 'Ошибка сохранения черновика материала - не найдена запись в базе данных!';
			
			// Выведем сообщение об ошибке
			echo json_encode($status);
			
			return;
		}*/
	}
	else $_POST['Draft'] = $db_material->Draft;
	//else{  trace($db_material->id); }
	//trace($db_material->id);
	// Установим пользователя для которого сохраняется черновик
	$_POST['UserID'] = auth()->user->id;
	
	// Очистим идентификатор оригинала что бы создать черновик материала
	//if ($_POST['Draft'])$_POST['MaterialID'] = $_POST['Draft'];
	//else { unset($_POST['MaterialID']);}
	unset($_POST['MaterialID']);
	// Сохраним черновик материала
	mdl_material_save2( $_POST, $db_material, $status );
	
	// Выведем форму материала
	$status['form'] = mdl_material_form2( $db_material, $db_structure );
	
	// Вернем ответ для клиента
	echo json_encode( $status );
}

/**
 * Контроллер для копирования материала
 * Поддерживает также копирование материала в другую локализацию сайта
 */
function material_copy( $material_id, $locale = NULL )
{	
	// Попытаемся найти материал в БД
	if( ifcmsmat( $material_id, $db_material, 'id') )
	{		
		// Попытаемся найти связь структуры с материалом 
		$db_cmsnavmats = _cmsnavmaterial()->find_all_by_MaterialID_and_Active( $material_id, 1 ) ;
		// Если мы не нашли связь структуры с материалом - создадим пустышку 		
		if( !isset($db_cmsnavmats) )  $db_cmsnavmats = array(); 	
		
		// Попытаемся найти все связи материала с дополнительными полями
		$db_cmsmatfields = _cmsmaterialfield()->find_all_by_Active_and_MaterialID_join_Field ( 1, $material_id );
		// Если доп поля материала не получены - оставим пустой массив
		if( !isset($db_cmsmatfields) ) $db_cmsmatfields = array();
		
		// Установим требуемую локаль 
		$oldlocale = locale( $locale );
		
		// Скопируем материал		
		$db_material = clone $db_material;	
		
		// Если нам не передана локаль - копируем в текущих таблицах
		if( !isset($locale) )
		{			
			// Заменим имя и URL материала что бы не было ошибок
			$db_material->Name = 'копия_' . $db_material->Name;
			$db_material->Url = 'copy_' . $db_material->Url;
			$db_material->save();
		}
		
		// Склонируем связи структур с данным материалом
		foreach ( $db_cmsnavmats as $db_cmsnavmat)
		{
			// Подставим новый ИД материала в текущий объект и скопируем его
			$db_cmsnavmat->MaterialID = $db_material->id;
			$db_cmsnavmat = clone $db_cmsnavmat;
		}
		
		// Склонируем связи материала с дополнительными полями
		foreach ( $db_cmsmatfields as $db_cmsmatfield) 
		{
			// Подставим новый ИД материала в текущий объект и скопируем его
			$db_cmsmatfield->MaterialID = $db_material->id;
			$db_cmsmatfield = clone $db_cmsmatfield;					
		}
		
		/*
		for ($j = 0; $j < sizeof($extrafields); $j++) 
		{
			$field = $extrafields[$j];
			$copy_field = clone $field;
			$copy_field->MaterialID = $db_material->id;
			$copy_field->save();
			if ($field->_field->Type == 5)
			{
				$table = _Scmstable()->find_all_by_Entity_and_Active('FTable_'.$field->id, 1);
				for ($i = 0; $i < sizeof($table); $i++) 
				{	
					$copy_row = clone $table[$i];
					$copy_row->Entity = 'FTable_'.$copy_field->id;
					$copy_row->save();
				}
			}
			
			if( dbSimplify::parse( 'field', $copy_field->FieldID, $db_field ) )			
		}
		*/
		
		// Выведем форму материала
		material_form( $db_material->id, $db_cmsnavmat->StructureID );	
	}
	// Не нашли вернемся к списку
	else header('Location:' . url()->base() . 'material');
}

/** Ассинхронное получние списка материалов */
function material_ajax_autocomplete()
{
	s()->async(TRUE);
	
	echo json_encode( mdl_material_autocomplete( $_POST['_data'] ) );
}

/** Очистить текст от HTML */
function material_ajax_clear_html()
{
	// Ассинхронный вывод
	s()->async(TRUE);
	
	// Если есть что преобразовывать
	if( isset( $_POST['_data'] ) )
	{				
		// Уберем все теги кроме картинок, ссылок и заголовков
		$text = strip_tags( $_POST['_data'], '<img><br><p>' );		
		
		// Уберем стили которые могли остаться в разрешенных тегах	
		$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text);		
		
		// Выведем полученный текст
		echo $text;		
	}
}

/** Отобразить форму для загрузки элемента на сервер */
function material_ajax_upload_form()
{
	// Ассинхронный вывод
	s()->async(TRUE);
	
	// Загрузим шаблон формы
	echo mout()->output('app/view/form/upload.php');
}

/** Ассинхронно загрузить файл на сервер */
function material_ajax_upload_save()
{
	// Ассинхронный вывод
	s()->async(TRUE);

	// Путь к папке с загрузками
	$upload_dir =  '/upload/';
	
	// Имя полученного файла
	$tmp_path = $_FILES['UploadFile']['tmp_name'];

	// Сгенерируем уникальное имя файла
	$file_name = rand( 0, 9999999999 ) . '_' . rand( 0, 9999999999 );
	
	// Обработаем тип получаемого файла
	$file_type = File::getExtension( $_FILES['UploadFile']['type'] );	

	// Если нам подошел тип файла
	if( $file_type !== FALSE )
	{
		// Получим полный путь к файлу
		$file_path = $upload_dir . $file_name . '.' . $file_type;
	
		// Обработаем загрузку файла
		if( move_uploaded_file( $tmp_path, getcwd() . $file_path ) )
		{	
			// Уникальный путь к ресурсу	
			$src = '/cms' . $file_path;
			
			// Сформируем правильное представление HTML	
			$tag = (File::isImage( $file_path ) ? '<img src="' . $src . '">' : '<a href="' . $src . '">Скачать файл</a>');
			
			// Вернем ответ в формате JSON
			echo json_encode(array(
				'tag' 	=> $tag,
				'path' 	=> $src,
				'type' 	=> $file_type
			));
		}
	}
}

function material_ajax_form_update()
{
	// Сохраним форму материала как черновик
	material_ajax_draft_save();
}

function material_delete_all_drafts()
{
	$drafts = _Material()->find_all_by_Draft(1);
	foreach ($drafts as $draft)	
	{
		$draft->delete();
	}
}

function material_ajax_form_test()
{
	s()->async(TRUE);
	
	print_r($_POST);
	
	echo '<br/>';
	
	print_r($_FILES);
	
	$uploaddir = 'upload/';
	
	if (move_uploaded_file($_FILES['upfile']['tmp_name'], $uploaddir,	$_FILES['upfile']['name'])) {
	    echo "File is valid, and was successfully uploaded.";
	} 
	else echo "There some errors!";
}

function material_ajax_clear_style()
{
	// Ассинхронный вывод
	s()->async(TRUE);

	// Если есть что преобразовывать
	if( isset( $_POST['_data'] ) )
	{	

		// Уберем стили 
		$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $_POST['_data']);
	
		// Выведем полученный текст
		echo $text;
	}
}
function material_img_edit( $material_id = NULL )
{
	if ($material_id) 
	{
		trace ($material_id);
		if ( dbSimplify::parse( 'material', $material_id, $db_material, 'MaterialID') )
		{
			trace ('Name = ' . $db_material->Name);
			$db_material->Content = str_replace('http://local.northport2.ru/', '', $db_material->Content);
			$db_material->save();
		}
		else trace ( 'Не нашли матенриал!!!' );
	}
	else
	{
		$db_materials = _Material()->find_all_by_Active(1);
		foreach ($db_materials as $db_material)
		{
			trace ('Name = ' . $db_material->Name);
			$db_material->Content = str_replace('images/', 'img/', $db_material->Content);
			$db_material->save();
		}
	}
}

function material_full_del()
{
	$db_materials = _Material()->find_all_by_Active(0);
	foreach ($db_materials as $db_material)
	{
		trace ('Name = ' . $db_material->Name);
		
		$db_material->delete();
	}
}

/**
 * Обработчик ассинхронного "живого" поиска
 * 
 * @param string $search 		Поисковый запрос
 * @param string $structure_id	Идентификатор ЭНС 
 */
function material_search( $search, $structure_id = 0 )
{
	s()->async(true);
	
	// СФормируем массив для ответа клиенту
	$responce = array( 'status' => '0', 'data'  => '', 'error' => '');
	
	// Попытаемся найти ЭНС
	$db_nav = cmsnav( $structure_id, 'id' );	
	 
	// Вернем содежимое таблицы
	$responce['data'] = mdl_material_table2( $db_nav, $pager, $search ); 
	
	// Сформируем ссылку на главную
	$pager_all_url = url()->build( url()->module(), $structure_id, 0 );
	
	// Установим пейджер
	$responce['pager'] = '<li>Отображать:</li><li><a class="__samson_pager_li active">Результаты поиска</li><li><a class="__samson_pager_li" href="'.$pager_all_url.'">Все</a></li>';

	// Ответим в формате JSON
	echo json_encode( $responce );
}

function material_ajax_order ($column, $structure_id = NULL)
{
	s()->async(true);
	$order = array('Name','Url','Modyfied');
	if (in_array($column, $order))
	{
		$_SESSION['Material_Table_Order_By'] = $column;
		if (isset($_SESSION['Material_Table_Order_Direction']))
		{
			if ($_SESSION['Material_Table_Order_Direction'] == 'DESC') $_SESSION['Material_Table_Order_Direction'] = 'ASC';
			else $_SESSION['Material_Table_Order_Direction'] = 'DESC';
		}
		else $_SESSION['Material_Table_Order_Direction'] = 'DESC';
		$pager = new samson\pager\Pager( NULL, 20, 'material/'.$structure_id );
		echo json_encode(array('data'=>mdl_material_table2($structure_id, $pager),'status'=>1,'error'=>''));
	}
}

function material_add_related($material_first, $material_second) 
{
	s()->async(true);
	$locale=locale();
	$relation = new related_materials(false);
	$relation->first_material = $material_first;
	$relation->first_locale = $locale;
	
	$relation->second_material = $material_second;
	$relation->second_locale = $locale;
	
	$relation->save();

	$cmsmaterial = CMSMaterial::get(array('MaterialID',$material_first), NULL, 0);
	$cmsmaterial = $cmsmaterial[0];

	echo mdl_material_submaterial_list($cmsmaterial->related());
}

function material_test()
{
	if (dbQuery('materialfield')->cond('Active', 1)->exec($db_materialfields))
	{
		trace(sizeof($db_materialfields));
		
		foreach ($db_materialfields as $db_materialfield)
		{
			$empty_mf = 0;
			$mat_del = 0;
			if (dbQuery('material')->cond('MaterialID', $db_materialfield->MaterialID)->first($db_material))
			{
				if ($db_material->Active = 0) $mat_del;
			}
			else $empty_mf++;
		}
		trace('empty - '.$empty_mf);
		trace('del - '. $mat_del);
	}
}
function material_del()
{
	if (dbQuery('materialfield')->cond('Active', 1)->exec($db_materialfields))
	{
		trace(sizeof($db_materialfields));
	
		foreach ($db_materialfields as $db_materialfield)
		{
			$db_materialfield->delete();
		}
	}
	if (dbQuery('material')->cond( 'Draft', 0, \samson\activerecord\dbRelation::NOT_EQUAL )->exec($db_materials))
	{
		foreach ($db_materials as $db_material)
		{
			trace ('Name = ' . $db_material->Name);
		
			$db_material->delete();
		}
	}
	
}

function material_form( $material_id )
{	
	$form = new \samson\cms\web\material\Form( $material_id );
	
	m()->html( $form->render() );
}
?>

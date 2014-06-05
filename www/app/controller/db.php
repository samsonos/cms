<?php
use samson\core\File;

/** Обработчик очистки таблицы SamsonCMS - "StructureMaterial" */
function db_clean()
{
	// HTML представление
	$output = '<h1>Очистка таблиц SamsonCMS</h1>';	
	
	$output .= '<br><h2>1. Очистка таблицы "Материалы"</h2>';
	// Получим все материалы
	$db_cmsmats = _cmsmaterial()->all();
	foreach ( $db_cmsmats as $db_cmsmat ) 
	{
		if( $db_cmsmat->Active == 0 ) {$output .= '<br> Удаление стертого материала: '.$db_cmsmat->id;$db_cmsmat->delete();}
		else if( $db_cmsmat->Draft > 0){$output .= '<br> Удаление черновика материала: '.$db_cmsmat->id;$db_cmsmat->delete();}
		else $db_cmsmats[ $db_cmsmat->id ] = $db_cmsmat;
	}
	
	$output .= '<br><h2>2. Очистка таблицы "Дополнительные поля"</h2>';
	// Получим все дополнительные поля
	$db_cmsfields = _field()->all();
	foreach ( $db_cmsfields as $db_cmsfield )
	{
		if( $db_cmsfield->Active == 0 ) {$output .= '<br> Удаление стертого материала: '.$db_cmsfield->id;$db_cmsfield->delete(); }		
		else $db_cmsfields[ $db_cmsfield->id ] = $db_cmsfield;
	}
	
	$output .= '<br><h2>3. Очистка таблицы "Структура"</h2>';
	// Получим все структуры
	$db_cmsnavs = _cmsnav()->all();
	foreach ( $db_cmsnavs as $db_cmsnav )
	{
		if( $db_cmsnav->Active == 0 ) {	$output .= '<br> Удаление стертой структуры: '.$db_cmsnav->id;$db_cmsnav->delete();	}
		else $db_cmsnavs[ $db_cmsnav->id ] = $db_cmsnav;
	}
	
	$output .= '<br><h2>4. Очистка таблицы "Материал - Дополнительные поля"</h2>';
	// Коллекция уникальных ключей
	$keys = array();
	
	// Получим все связи материала с дополнительные поля
	$db_cmsmaterialfields = _cmsmaterialfield()->all();
	foreach ( $db_cmsmaterialfields as $db_cmsmaterialfield )
	{
		// Получим уникальный ключ
		$ukey = $db_cmsmaterialfield->MaterialID.'-'.$db_cmsmaterialfield->FieldID;
		
		// Если это стертая связь
		if( $db_cmsmaterialfield->Active == 0 ) {$output .= '<br> Удаление стертой связи материала с доп. полем: '.$db_cmsmaterialfield->id;$db_cmsmaterialfield->delete(); }
		// Проверим связь на существование материала
		else if( !isset($db_cmsmats[$db_cmsmaterialfield->MaterialID]) ){$output .= '<br> Удаление связи по материалу: '.$db_cmsmaterialfield->MaterialID;$db_cmsmaterialfield->delete();	}
		// Проверим связь на существование структуры
		else if( !isset($db_cmsfields[$db_cmsmaterialfield->FieldID]) ){	$output .= '<br> Удаление связи по доп.полю: '.$db_cmsmaterialfield->FieldID; $db_cmsmaterialfield->delete();	}		
		// Если это уникальный ключ запишем его
		else if( ! isset( $keys[ $ukey ] ) ) $keys[ $ukey ] = 0;
		// Это дубль ключа - удалим
		else
		{
			$output .= '<br> Удаление дубля связи материала с доп. полем: '.$db_cmsmaterialfield->id;
			$db_cmsmaterialfield->delete();
		}		
	}	
	
	
	$output .= '<br><h2>5. Очистка таблицы "Структура-Материал"</h2>';
	// Получим все записи из таблицы БД
	$db_cmsnavmats = _cmsnavmaterial()->all();
	
	// Коллекция уникальных ключей
	$keys = array();
	
	// Переберем все связи структуры с материалом
	foreach ( $db_cmsnavmats as $db_cmsnavmat ) 
	{
		// Получим уникальный ключ
		$ukey = $db_cmsnavmat->StructureID.'-'.$db_cmsnavmat->MaterialID;
		
		// Если это стертая связь
		if( $db_cmsnavmat->Active == 0 ) {$output .= '<br> Удаление стертой связи структуры с материалом: '.$db_cmsnavmat->id;$db_cmsnavmat->delete();	}		
		// Проверим связь на существование материала
		else if( !isset($db_cmsmats[$db_cmsnavmat->MaterialID]) ){$output .= '<br> Удаление связи по материалу: '.$db_cmsnavmat->MaterialID;$db_cmsnavmat->delete();	}
		// Проверим связь на существование структуры
		else if( !isset($db_cmsnavs[$db_cmsnavmat->StructureID]) ){ $output .= '<br> Удаление связи по структуре: '.$db_cmsnavmat->StructureID; $db_cmsnavmat->delete();}
		// Если это уникальный ключ запишем его 
		else if( ! isset( $keys[ $ukey ] ) ) $keys[ $ukey ] = 0;
		// Это дубль ключа - удалим
		else 
		{
			$output .= '<br> Удаление дубля связи структуры с материалом: '.$db_cmsnavmat->id;
			$db_cmsnavmat->delete();		
		}
	}			
	
	$output .= '<br><h2>6. Очистка таблицы "Структура-Доп. поле"</h2>';
	// Получим все записи из таблицы БД
	$db_cmsnavfields = _cmsnavfield()->all();
	
	// Коллекция уникальных ключей
	$keys = array();
	
	// Переберем все связи структуры с материалом
	foreach ( $db_cmsnavfields as $db_cmsnavfield )
	{
		// Получим уникальный ключ
		$ukey = $db_cmsnavfield->StructureID.'-'.$db_cmsnavfield->FieldID;
	
		// Если это стертая связь
		if( $db_cmsnavfield->Active == 0 ) {
			$output .= '<br> Удаление стертой связи структуры с доп.полем: '.$db_cmsnavfield->id;$db_cmsnavfield->delete();
		}
		// Проверим связь на существование доп.поля
		else if( !isset($db_cmsfields[$db_cmsnavfield->FieldID]) ){
			$output .= '<br> Удаление связи по доп. полю: '.$db_cmsnavfield->FieldID;$db_cmsnavfield->delete();
		}
		// Проверим связь на существование структуры
		else if( !isset($db_cmsnavs[$db_cmsnavfield->StructureID]) ){
			$output .= '<br> Удаление связи по структуре: '.$db_cmsnavfield->StructureID; $db_cmsnavfield->delete();
		}
		// Если это уникальный ключ запишем его
		else if( ! isset( $keys[ $ukey ] ) ) $keys[ $ukey ] = 0;
		// Это дубль ключа - удалим
		else
		{
			$output .= '<br> Удаление дубля связи структуры с доп. полем: '.$db_cmsnavfield->id;
			$db_cmsnavfield->delete();
		}
	}
	
	// Выведем представление
	m()->title('Очистка базы данных')->html( $output );
}

/** Обработчик очистки неиспользуемых картинок галлереии */
function db_clean_gallery()
{
	// Если галлерея не подключена
	if( !function_exists('_gallery')) return false;
	
	// Получим путь к файлам галлереи
	$gal_folder_path = __SAMSON_CWD__.'/upload/gallery';
	
	// Вывод
	$html = '<br> Очистка "Галлереи" - "'.$gal_folder_path.'"';
	
	// Получим файлы из галлереи
	$files = File::dir( $gal_folder_path );
	
	// Получим все данные из галлереи
	if(dbQuery('gallery')->exec($db_images))
	{
		$html .= '<br> 1. В БД найдено: "'.sizeof($db_images).'" записей, Файлов: "'.sizeof($files).'"';
		$html .= '<br> 2. Очистка записей в БД';
		
		// Переберем все файлы которые должны лежать в папке галлереи
		foreach ( $db_images as $img ) 
		{			
			// Построим полный путь к картинке
			$img_path = __SAMSON_CWD__.$img->Path;
			
			// Если запись удалена - очистим её
			if( ! $img->Active || !in_array( $img_path, $files )  ) 
			{
				// Удалим запись в БД
				$img->delete();
				
				$html .= '<br>  - Удаление не существующей записи из галлереи №'.$img->id.'-"'.$img_path.'"';
			}		
		}
	}
	
	// Получим все данные из галлереи
    if(dbQuery('gallery')->exec($db_images))
	{			
		$html .= '<br> 3. Очистка файлов галлереи';
		
		// Сформируем массив путей к картинка полученных из БД
		$imgs_path = array(); 
		
		// Переберем все файлы которые должны лежать в папке галлереи
		foreach ( $db_images as $img ) 
		{
			// Запишем имя картинки
			$imgs_path[] = __SAMSON_CWD__.$img->Path;
			
			//  Сформируем имя тамбнеила
			$imgs_path[] = dirname(__SAMSON_CWD__.$img->Path).'/'.pathinfo($img->Path, PATHINFO_FILENAME).'_thumb.'.pathinfo($img->Path, PATHINFO_EXTENSION);			
		}	
		
		// Коллекция файлов для удаления
		$delete_files = array();
		
		// Переберем все файлы в папки галлереи, и если этого файла нет в эталонной коллекции картинок
		foreach ( $files as $file ) if( !in_array( $file, $imgs_path ) ) 
		{
			$html .= '<br> Удаление картинки: '.$file;
			
			// Удалим файл
			File::clear( $file );			
		}
	}
	
	// Выведем представление
	m()->title('Очистка Галлереи')->html($html);
}

/** Очистить папку загрузок */
function db_clean_upload()
{
	// Коллекция файлов используемых в материалах
	$found_files = array(); 
	
	// Папка для загрузки
	$upload_path = __SAMSON_CWD__.'/upload';
	
	// Получим файлы из галлереи
	$files = File::dir( $upload_path );
	
	// Вывод
	$html = '<br> Очистка "Загрузок" - "upload"';
	
	// Получим все материалы
    if(dbQuery('material')->exec($db_materials))
	{
		// Переберем все материалы
		foreach ( $db_materials as $db_material ) 
		{
			// Попытаемся найти упоминание 
			if( preg_match_all('/cms\/upload\/([^\'\"]*)/iu', $db_material->Content, $matches))
			{
				// Если мы нашли имена файлов из upload
				if( isset($matches[1]) ) foreach ( $matches[1] as $match ) 
				{
					// Если путь лежит через роутер ресурсов
					if( false !== ($pos = strpos( $match, '&'))) $match = substr( $match, 0, $pos );
					
					// Добавим полный путь
					$found_files[ $match ] = $upload_path.'/'.$match;
				}				
			} 
		}
	}
	
	// Получим дополнительные поля материалов
    if(dbQuery('materialfield')->exec($db_materialfields))
	{
		foreach( $db_materialfields as $db_materialfield ) 
		{
			// Попытаемся найти упоминание 
			if( preg_match_all('/cms\/upload\/([^\'\"]*)/iu', $db_materialfield->Value, $matches))
			{
				// Если мы нашли имена файлов из upload
				if( isset($matches[1]) ) foreach ( $matches[1] as $match ) 
				{
					// Если путь лежит через роутер ресурсов
					if( false !== ($pos = strpos( $match, '&'))) $match = substr( $match, 0, $pos );
					
					// Добавим полный путь
					$found_files[ $match ] = $upload_path.'/'.$match;
				}				
			} 
		}
	}
	
	// Переберем все файлы в папки галлереи, и если этого файла нет в эталонной коллекции картинок
	foreach ( $files as $file ) if( !in_array( $file, $found_files ) )
	{
		// Вложенную папку пропускаем
		if( strpos( dirname($file), '/gallery' ) == FALSE )	
		{
			$html .= '<br> Удаление файла: '.$file;		
			
			// Удалим файл
			File::clear( $file );
		}
	}	
	
	// Выведем представление
	m()->title('Очистка Загрузок')->html($html);
}
?>
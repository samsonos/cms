<?php

use Samson\ActiveRecord\dbSimplify;
use Samson\ActiveRecord\gallery;
use samson\core\File;

function gallery_ajax_upload_img( $material_id ) 
{
	// Ассинхронный вывод
	s()->async(TRUE);

	// Безопасно получим текущий материал
	if ( dbSimplify::parse('material', $material_id, $db_material) )
	{			
		// Путь к папке с загрузками
		$upload_dir =  '/upload/gallery/';
		
		// Получим количество переданных фотографий
		$file_count = sizeof( $_FILES['UploadFile']['tmp_name'] );
		
		// Определим переменную для ответа
		$all_tag = '';
		// Переберем все фотографии переданные 
		for ($i = 0; $i < $file_count; $i++) 
		{
			// Имя полученного файла
			$tmp_path = $_FILES['UploadFile']['tmp_name'][$i];
			
			// Сгенерируем уникальное имя файла
			$file_name = 'gallery_' . rand( 0, 9999999999 ) . '_' . rand( 0, 9999999999 );
			
			// Обработаем тип получаемого файла
			$file_type = File::getExtension( $_FILES['UploadFile']['type'][$i]);	

			// Если нам подошел тип файла
			if( isset( File::$ImageExtension[ $file_type ] ) )
			{
				// Получим полный путь к файлу
				$file_path = $upload_dir . $file_name . '.' . $file_type;
			
				// Обработаем загрузку файла
				if( move_uploaded_file( $tmp_path, getcwd() . $file_path ) )
				{								    			
					// Получим текущую фотографию
					if (( $file_type == 'jpg' ) || ( $file_type == 'jpeg' ))
					$img = imagecreatefromjpeg( getcwd() . $file_path );
					elseif ( $file_type == 'png' )
					$img = imagecreatefrompng( getcwd() . $file_path );
					elseif ( $file_type == 'gif' )
					$img = imagecreatefromgif( getcwd() . $file_path );
					
					// Получим текущие размеры картинки
					$sWidth = imagesx( $img );
					$sHeight = imagesy( $img );
						
					// Получим соотношение сторон картинки
					$originRatio = $sHeight / $sWidth;
					$tHeight = 150;
					$tWidth = 200;
					// Получим соотношение сторон в коробке
					$tRatio = $tHeight / $tWidth;
					
					// Сравним соотношение сторон картинки и "целевой" коробки для определения
					// по какой стороне будем уменьшать картинку
					if ( $originRatio < $tRatio)
					{
						$width = $tWidth;
						$height = $width * $originRatio;
					}
					else
					{
						$height = $tHeight;
						$width = $height / $originRatio;
					}
					// Зададим расмер превьюшки
					$new_width = floor( $width );
					$new_height = floor( $height );
					
					// Создадим временный файл
					$tmp_img = imagecreateTRUEcolor( $new_width, $new_height );
					
					// Скопируем, изменив размер
					imagecopyresized ( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $sWidth, $sHeight );
					
					// Получим полный путь к превьюхе
					$thumb_path = getcwd() . $upload_dir . $file_name . '_thumb.' . $file_type;
										
					//Сохраним временную картинку в файл
					if (( $file_type == 'jpg' ) || ( $file_type == 'jpeg' ))
					imagejpeg( $tmp_img, $thumb_path );
					elseif ($file_type == 'png')
					imagepng( $tmp_img, $thumb_path );
					elseif ($file_type == 'gif')
					imagegif( $tmp_img, $thumb_path );
					
					// Уникальный путь к ресурсу
					$src = '/cms'.$file_path;
					// Уникальный путь к превьюхе
					$thumb_src = '/cms'.$thumb_path;
					
					$db_gallery = new Gallery();
					$db_gallery->MaterialID = $db_material->id;
					$db_gallery->Path = $file_path;
					$db_gallery->Src = $src;
					$db_gallery->Thambpath = $thumb_path;
					$db_gallery->Thumbsrc = $thumb_src;
					$db_gallery->Name = $_POST['Name'];
					$db_gallery->Active = 1;
					$db_gallery->save();
					
					// Сформируем правильное представление HTML	
					$tag = '<a href="gallery/ajax_delete/' .  $db_gallery->id . '" class="GalBtnDelete" title="Удалить фотографию">X</a><img src="' . $src . '">';
					
					//Сохраним тег
					$all_tag .= '<li>' . $tag . '</li>';
				}
				// Сохраним ошибку
				else $error =  'Ошибка при сохранении фотографии';
			}
			// Сохраним ошибку
			else $error = 'Вы пытаетесь загрузить не фотографию';
		}
		echo json_encode(array(
						'tag' 	=> $all_tag
		 			));
	}
	else // Вернем ответ в формате JSON
			echo json_encode(array(
				'error' 	=> 'Это не существующий материал'
			));
}

function gallery_ajax_upload_form( $material_id )
{	
	// Ассинхронный вывод
	s()->async(TRUE);
	
	// Выведем форму
	echo mout()
		->set( 'material_id', $material_id )
		->output( 'upload' );
}

function gallery_ajax_editor( $gallery_id )
{
	// Ассинхронный вывод
	s()->async(TRUE);
		
	//Если галерея существуем отправим ее на редактирование
	if (dbSimplify::query( _Gallery()->find_all_by_PhotoID_and_Active($gallery_id, 1), $db_gallery ) )
	{
		// Выведем форму
		echo m()
			->set( 'image_src', $db_gallery->Src )
			->set( array('db_gallery' => $db_gallery) )	
			->output('app/view/editor.php');
	}
}

/**
 * Контроллер для ассинхронной обрабатки "обрезки" изображения
 */
function gallery_ajax_cut()
{	
	// Это ассинхронный ответ
	s()->async(TRUE);
	
	// Статум для возвращения серверу
	$status = array();
	
	// Безопасно получим указатель на ссылку картинки в БД
	if (dbSimplify::parse( 'gallery', $_POST['GalleryID'], $db_gallery) )
	{
		// Координата Х левого верхнего угла вырезаемой области
		$x = $_POST['Left'];
		// Координата У левого верхнего угла вырезаемой области
		$y = $_POST['Top'];
		// Длина вырезаемой области
		$width = $_POST['Width'];
		// Ширина вырезаемой области
		$height = $_POST['Height'];
		
		// Получим полный путь к картинке
		$file_path = getcwd() .  $db_gallery->Path;

		// Указатель на картинку
		$img = NULL;
		
		// Определим тип картинки и прочитаем её
		$file_ext = pathinfo( $file_path, PATHINFO_EXTENSION );		
		switch( $file_ext )
		{
			case 'jpg'	:
			case 'jpeg'	: $img = imagecreatefromjpeg( $file_path ); break;
			case 'png'	: $img = imagecreatefrompng( $file_path ); break;
			case 'gif'	: $img = imagecreatefromgif( $file_path ); break;
			default 	: $status['error'] = 'Тип картинки(' . $file_ext . ') не поддерживается'; 
		}	

		// Если мы смогли прочитать исходную картинку
		if( isset( $img ) )
		{	
			
			// Создадим временный файл
			$tmp_img = imagecreateTRUEcolor( $width, $height );
			
			// Изменим размер
			if ( imagecopyresized ( $tmp_img, $img, 0, 0, $x, $y, $width, $height, $width, $height ) ) 
			{
				// Перепишем исходную картинку
				switch( $file_ext )
				{
					case 'jpg'	:
					case 'jpeg'	: imagejpeg( $tmp_img, $file_path, 95 ); break;
					case 'png'	: imagepng( $tmp_img, $file_path, 9 ); break;
					case 'gif'	: imagegif( $tmp_img, $file_path );	
				}
	
				$status['tag'] = '<img src="' . $db_gallery->Src . '">';
				$status['src'] = $db_gallery->Src;
			}	
		}
		// По каким-то пречинам картинка не прочитана
		else $status['error'] = 'Не удалось прочитать картинку(' . $file_path . ')';
	}
	// Вернем описание ошибки
	else $status['error'] = 'Требуемая картинка(' . $_POST['GalleryID'] . ') не найдена в БД';
	
	
	// Веренем серверу статус выполнени метода
	echo json_encode($status);
}

function gallery_ajax_scope($_data)
{
	// Это ассинхронный ответ
	s()->async(TRUE);

	// Статум для возвращения серверу
	$status = array();
	
	// Распарсим переданные данные
	$_data = explode( '|', $_data );
	$gallery_id = $_data[0];
	$width = $_data[1];
	$height = $_data[2];
	// Безопасно получим указатель на ссылку картинки в БД
	if (dbSimplify::parse( 'gallery', $gallery_id, $db_gallery) )
	{
		mdl_gallery_scale($db_gallery, $width, $height);
		echo $db_gallery->src;
	}
}

function gallery_ajax_delete( $gallery_id )
{
	// Ассинхронный вывод
	s()->async(TRUE);
	// Безопасно найдем запрашиваемую к удалению фотографию
	if (dbSimplify::parse( 'gallery', $gallery_id, $db_gallery ) )
	{
		// Сохраним текущий материал
		$material_id = $db_gallery->MaterialID;
		
		//Удалим требуемую фотографию
		$db_gallery->Active = 0;
		$db_gallery->save();
	}
	else echo 'error';
}

?>

<?php

function mdl_gallery_scale( & $db_gallery, $width, $height)
{
	$status = array(); 
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
		// Получим текущие размеры картинки
		$sWidth = imagesx( $img );
		$sHeight = imagesy( $img );
		
		// Зададим расмер превьюшки
		$new_width = floor( $width );
		$new_height = floor( $height ); 
		
		// Создадим временный файл
		$tmp_img = imagecreateTRUEcolor( $width, $height );
			
		// Изменим размер
		if ( imagecopyresized ( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $sWidth, $sHeight ) )
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
		}
	}
}
?>
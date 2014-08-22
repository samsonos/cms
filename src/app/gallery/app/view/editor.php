<form method="post" class="form2" action="<?php echo url()->base();?>gallery/ajax_cut/" enctype="multipart/form-data" >
	<input type="hidden" name="GalleryID" id="GalleryID" value="<?php echo $db_gallery->id; ?>">
	<input type="hidden" name="Width" id="Width" value="">
	<input type="hidden" name="Height" id="Height" value="">
	<input type="hidden" name="Left" id="Left" value="">
	<input type="hidden" name="Top" id="Top" value="">
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
	<h2 class="header">Редактирование изображения</h2>
	<ul class="table-like-list">			
		<li title="Описание изображения"><label>Заголовок:</label><div class="input-text"><input name="Name" id="Name" value="" class=""></div></li>	
		<li title="Размеры" class="tll-classic">
			<label>Ширина:</label><div title="Ширина изображения" class="input-text image-width" ><input name="imageNewWidth" id="imageNewWidth" value="" class=""></div>
			<label>Высота:</label><div title="Высота изображения" class="input-text image-height"><input name="imageNewHeight" id="imageNewHeight" value="" class=""></div>
			<input title="Изменить размеры изображение" type="button" name="btnEditImage" id="btnEditImage" value="Изменить размеры" class="">
			<input title="Изменять пропорционально" type="checkbox" name="btnRateablyImage" id="btnRateablyImage" class="">	пропорционально	
		</li>	
		<li title="Размеры" class="tll-classic">
			<label>Ширина:</label><div title="Ширина изображения" class="input-text image-width"><input name="imageWidth" id="imageWidth" value="" class=""></div>
			<label>Высота:</label><div title="Высота изображения" class="input-text image-height"><input name="imageHeight" id="imageHeight" value="" class=""></div>			
		</li>
		<li title="Редактор изображения">
			<div class="image-editor">
				<img src="<?php v('image_src'); ?>">
			</div>
		</li>
		<li class="ttl-spacer"></li>
		<li class="right-align">
			<input title="Удалить изображение" type="button" name="btnDeleteImage" id="btnDeleteImage" value="Удалить" class="">
			<input title="Сохранить изображение" type="submit" name="btnSaveImage" id="btnSaveImage" value="Сохранить" class="">
		</li>
	</ul>
</form>
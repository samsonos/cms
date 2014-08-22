<?php ?>
<!-- НАЧАЛО шаблона формы загрузки фотографии на сервер -->
<form method="post" class="form2" action="<?php url_base('gallery/ajax_upload_img', 'material_id')?>" enctype="multipart/form-data" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
	<h2 class="header">Загрузка фотографии в галерею</h2>
	<ul class="table-like-list">
		<li title="Изображение"><div class="input-text"><input type="file" name="UploadFile[]" id="UploadImage"  multiple></div></li>	
		<li title="Описание изображения"><label>Заголовок:</label><div class="input-text"><input name="Name" id="Name" value="" class=""></div></li>
		<li title="Загрузить данные" class="right-align"><input type="submit" name="btnUploadFile" id="btnUploadFile" value="Загрузить" class=""></li>
	</ul>
	<div id="UploadImgFull"></div>
</form>
<!-- КОНЕЦ шаблона формы загрузки фотографии на сервер -->
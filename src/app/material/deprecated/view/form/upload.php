<!-- НАЧАЛО шаблона формы загрузки элемента на сервер -->
<form method="post" class="form2" action="<?php echo url()->base();?>material/ajax_upload_save/" enctype="multipart/form-data" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
	<h2 class="header">Загрузка данных</h2>	  
	<ul class="table-like-list">
		<li title="Название поля"><div class="input-text"><input type="file" name="UploadFile" id="UploadFile"></div></li>		
		<li title="Загрузить данные" class="right-align"><input type="submit" name="btnUploadFile" id="btnUploadFile" value="Загрузить" class=""></li>		
	</ul>
</form>
<!-- КОНЕЦ шаблона формы загрузки элемента на сервер -->
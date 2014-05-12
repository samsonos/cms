<!-- НАЧАЛО шаблона формы элемента структуры сайта -->
<form method="post" class="form2" action="structure/delete" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
	<div class="header">Вы подтверждаете удаление ЭСС: "<?php v('structure_name')?>"?</div>		
	<?php echo auth()->create_token( 'structure_token'); ?>
	<input type="hidden" name="StructureID" id="StructureID" value="<?php v('cmsnav_id')?>">
	<input type="submit" value="Да" class="">
	<input type="button" value="Нет" class="close-button">
</form>
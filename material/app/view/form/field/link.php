<div class="field_resource">
	<textarea name="Field_<?php v('field_id')?>"><?php v('cmsmaterialfield_Value') ?></textarea>						
	<input class="btnUploadField" type="button" value="Загрузить" title="Вставить в поле материала внешний ресурс(Картинку, Текстовый файл, Документ MS Word)">	
	<label>	
<?php if(isv('cmsmaterialfield_Value')):?>						
	<?php if( !isval('image') ):?>	
		<img src="<?php v('cmsmaterialfield_Value')?>" title="Ресурс поля материала">
	<?php else:?>
		<a href="<?php v('cmsmaterialfield_Value')?>">Ссылка на ресурс(<?php v('extension')?>)</a>
	<?php endif?>
		<input type="button" class="btnUploadFieldClear" value="Удалить">
<?php endif?>
	</label>
	<div class="clear"></div>
</div>
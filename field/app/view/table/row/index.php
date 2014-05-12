<tr>
	<td class="id"><?php v('field_id');?></td>				
	<td class="name"><?php v('Name_field') ?></td>
	<td class="type"><?php v('Type_field');?></td>
	<td class="value"><?php v('Value_field');?></td>
	<td class="description"><?php v('Description_field');?></td>
	<td class="nav"><?php v('cmsnav_Name');?></td>	
	<td class="control">				
		<a class="control icon2 icon_16x16 icon-edit" href="<?php url_base('field/form','field_id')?>" title="Редактировать текущее доп. поле"></a>	
		<a class="control icon2 icon_16x16 icon-copy-material copy" href="<?php url_base('field/copy','cmsmaterial_id')?>" title="Создать копию доп. поля"></a>
		<a class="control icon2 icon_16x16 icon-delete delete" href="<?php url_base('field/ajax_delete', 'parent_cmsnav' ,'cmsmaterial_id', 'samsonpager_current_page')?>" title="Удалить текущее доп. поле"></a>					
	</td>			
</tr>
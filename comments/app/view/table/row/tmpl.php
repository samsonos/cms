<tr>
	<td class="control">
		<a href="<?php module_url('ajax_form','comment_id'); ?>" class="btnEditComment icon2 left icon_16x16 icon-edit" title="Редактировать выбранный комментарий"></a>
		<a href="<?php module_url('ajax_delete','comment_id'); ?>" class="btnDeleteComment icon2 left icon_16x16 icon-delete" title="Удалить выбранный комментарий"></a>
	</td>
	<td class="author"><?php v('comment_Author');?></td>					
	<td class="email"><?php v('comment_Email');?></td>
	<td class="text"><?php v('comment_Text');?></td>
	<td class="material"><?php v('comment_material_Name');?></td>
	<td class="moderate" title="Опубликовать/Скрыть комментарий">
		<a class="publish_href" href="<?php module_url('ajax_publish', 'comment_id' ) ?>"></a>
	<?php if(!isval('cmsmaterial_Published') ):?>
		<input type="checkbox" value="<?php v('comment_id'); ?>" name="moderate" id="moderate" checked>
	<?php else:?>
		<input type="checkbox" value="<?php v('comment_id'); ?>" name="moderate" id="moderate">
	<?php endif?>
	</td>
	<td class="created"><?php v('comment_Created');?><br><?php v('comment_Modyfied');?></td>			
</tr>
<tr>
	<td class="control">
		<a href="user/ajax_form/<?php v('user_id'); ?>" class="btnEditUser icon2 left icon_16x16 icon-edit" title="Редактировать выбранного пользователя"></a>
		<a href="user/ajax_delete/<?php v('user_id'); ?>" class="btnDeleteUser icon2 left icon_16x16 icon-delete" title="Удалить выбранного пользователя"></a>
	</td>
	<td class="id"><?php v('user_id');?></td>
	<td class="fio"><a href="user/ajax_form/<?php v('user_id'); ?>"><?php v('user_FName');?> <?php v('user_SName');?> <?php v('user_TName');?></a></td>					
	<td class="email"><?php v('user_Email');?></td>
	<td class="created"><?php v('user_Created');?><br><?php v('user_Modyfied');?></td>			
</tr>
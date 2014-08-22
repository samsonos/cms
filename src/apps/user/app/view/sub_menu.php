<li>
	<a class="sub_menu_a <?php if(isv('all_materials')):?>active<?php endif?>" href="<?php url_base('material')?>">
    <?php t('Все')?>
	</a>
</li>
<li>
	<a id="btnCreateUser" class="sub_menu_a <?php if(isv('new_material')):?>active<?php endif?>" href="<?php url_base( 'user/ajax_form' );?>">
		<span class="icon icon-add-user">.</span><?php t('Создать пользователя')?>
	</a>
</li>
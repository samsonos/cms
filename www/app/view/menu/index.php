<nav>
	<ul class="main_menu">
		<li class="icons">
			<a class="to_main<?if(isv('active')) echo ' active'?>" href="<?php url_base()?>" title="<?php t('На главную')?>">.</a>
		</li><li class="icons">
			<a class="to_site" href="<?php url_base('control/site');?>" title="<?php t('Открыть сайт')?>">.</a>
		</li><li class="icons">
			<a class="edit_site" href="<?php url_base('control/editor')?>" title="<?php t('Редактировать сайт')?>">.</a>
		</li>
		
		<?php v('items')?>		
	</ul>
	
	<aside class="r_b">
        <?php if(defined('__SAMSONCMS_LOGO')):?>
		    <div class="logo"></div>
        <?php endif?>
        <?php m('i18n')->render('list')?>
		<a href="<?php url_base('auth2','logout')?>" class="logout" title="<?php t('Выход')?>">.</a>
	</aside>
</nav>

<div class="sub_menu_wrapper <?php isv('submenu','control')?>">
	<?php if(isv('submenu')):?>
	<ul class="sub_menu">
		<?php iv('submenu');?>
	</ul>
	<?php endif;?>
</div>
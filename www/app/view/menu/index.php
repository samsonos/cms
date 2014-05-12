<nav>
	<ul class="main_menu">
		<li class="icons">
			<a class="to_main<?if(isv('active')) echo ' active'?>" href="<?php url_base()?>" title="На главную">.</a>
		</li><li class="icons">
			<a class="to_site" href="<?php url_base('control/site');?>" title="Открыть сайт">.</a>
		</li><li class="icons">
			<a class="edit_site" href="<?php url_base('control/editor')?>" title="Редактировать сайт">.</a>
		</li>
		
		<?php v('items')?>		
	</ul>
	
	<aside class="r_b">
		<div class="logo"></div>
		<a href="<?php url_base('auth2','logout')?>" class="logout" title="Выход">.</a>		
	</aside>
</nav>

<div class="sub_menu_wrapper <?php isv('submenu','control')?>">
	<?php if(isv('submenu')):?>
	<ul class="sub_menu">
		<?php iv('submenu');?>
	</ul>
	<?php endif;?>
</div>
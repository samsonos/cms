<li>
	<a class="ie-css3 <?php if(!isv('cmsnav_id')):?>active<?php endif?>" href="<?php url_base('structure');?>" title="Перейти к корню дерева элементов структуры сайта">Все</a>
</li>
<li title="Создать новое дополнительное поле">
	<a class="ie-css3 icon2 icon-add-field" href="<?php url_base( 'field/form' );?>">
		<span class="name2">Новое доп. поле</span>	
	</a>
</li>
<li>
	<a class="ie-css3 btn copy icon2 icon-nav-copy" title="Скопировать навигацию сайта в другую локаль">		
		<span class="name2">Скопировать в</span>		
		<select id="clone_locale">
			<?php if(locale()!=''):?><option value="ru">RU</option><?php endif?>
			<?php if(locale()!='ua'):?><option value="ua">UA</option><?php endif?>
			<?php if(locale()!='en'):?><option value="en">EN</option><?php endif?>				
		</select>
	</a>		
</li>
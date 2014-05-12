<div class="structure clear">	
	<div class="top">
	<?php if(isv('parent_id')):?>
		<h1 class="icon2 icon_16x16 left icon-structure" title="Элемент структуры сайта">
			<a href="structure/<?php v('parent_id');?>" title="Элемент структуры сайта (ЭСС)"><?php v('parent_Name');?></a>
			<img src="<?php src('img/list_arrow.png','local'); ?>" >&nbsp;
		</h1>	
	<?php endif?>	
		<h1 class="icon2 icon_16x16 left icon-structure" title="Элемент структуры сайта">Структура материалов:</h1>			
		<div class="search"><input name="search" id="search" value="Укажите поисковый запрос..."></div>
	</div>		
<?php if(isv('parent_id')):?>
	<div class="breadcrumps clear"><a href="structure">Вернуться к началу</a></div>		
<?php endif?>
	<div class="tree-container"><?php iv('tree'); ?></div>		
</div>
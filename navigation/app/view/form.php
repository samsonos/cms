<!-- НАЧАЛО шаблона формы элемента структуры сайта -->
<form method="post" class="form2" action="<?php url_base('structure/save','cmsnav_id')?>" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>	
	<?php if( isval( 'cmsnav_id')):?>		  
		<div class="header">Создание <span title="Элемент структуры сайта">ЭСС </span></div>
	<?php else:?>
		<div class="header">Редактирование <span title="Элемент структуры сайта">ЭСС:</span> <span class="bold"><?php v('cmsnav_Name') ?></span></div>
	<?php endif?>	
	<?php echo auth()->create_token( 'structure_token'); ?>
	<input type="hidden" name="Active" id="Active" value="1">
	<input type="hidden" name="Created" id="Created" value="<?php v('cmsnav_Created') ?>">
	<input type="hidden" name="StructureID" id="StructureID" value="<?php v('cmsnav_id') ?>">
	<input type="hidden" name="MaterialID" id="std_material_id" value="<?php v('cmsnav_MaterialID')?>">
	<input type="hidden" name="UserID" id="std_user_id" value="<?php echo auth()->user->id; ?>">	    
	<ul class="table-like-list">
		<li title="Родительский элемент структуры сайта"><label>Родитель:</label><select name="ParentID" id="parent_id"><?php echo $parent_select; ?></select></li>
		<li title="Наименование элемента структуры сайта">
			<label>Наименование:</label>
			<div class="input-text"><input name="Name" id="Name" value="<?php v('cmsnav_Name')?>"></div>
		</li>
		<li title="Уникальный локатор элемента структуры сайта(URL)">
			<label>URL:</label>
			<div class="input-text"><input name="Url" id="Url" value="<?php v('cmsnav_Url')?>"></div>
		</li>
		<li title="Материал SamsonCMS который открывается при локации на данный элемент структуры сайта">
		<?php if(isval('cmsmaterial_id')):?>
			<label>Материал по умолчанию:</label>
		<?php else:?>
			<label>Материал по умолчанию(<a class="btn_open_std_material" href="<?php url_base('material/form', 'cmsnav_MaterialID')?>" title="Открыть материал по умолчанию">Открыть</a>):</label>
		<?php endif?>
			<div class="input-text"><input name="std_material" id="std_material" value="<?php v('cmsmaterial_Name')?>"></div>		
		</li>
	<?php if( isval( 'cmsnav_id')):?>
		<li title="Добавить новый элемент структуры сайта" class="right-align"><input type="submit" value="Добавить" class=""></li>
	<?php else:?>
		<li title="Сохранить элемент структуры сайта" class="right-align"><input type="submit" value="Сохранить" class=""></li>
	<?php endif?>	
	</ul>
</form>
<!-- КОНЕЦ шаблона формы элемента структуры сайта -->
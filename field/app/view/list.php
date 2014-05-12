<?php 
// Переходники для работы с данными представления из контроллера
if( isset( $db_structure) ) $db_structure = & $db_structure;
if( isset( $items) ) $items = & $items;
?>
<!-- НАЧАЛО шаблона списка полей элемента структуры сайта -->
<form method="post" class="form2 field-list" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>	
	<h2 class="header">Список полей для:<br><span class="bold"><?php echo $db_structure->Name; ?></span></h2>	
	<input type="hidden" name="StructureID" id="StructureID" value="<?php echo $db_structure->id; ?>"> 	
	<ul class="item-list">
		<?php if( ! isset($items) ):?>
			<li class="no-data">Полей на данный момент - нет</li>
		<?php else:?>		
			<?php foreach ($items as $db_field):?>		
			<li title="<?php echo $db_field->Description; ?>">
				<span class="title"><?php echo $db_field->Name; ?></span>
				<a class="field_id" style="display:none;"><?php echo $db_field->id; ?></a>
				<a class="icon2 icon_16x16 icon-edit edit-field-button" href="field/ajax_form/<?php echo $db_structure->id ?>/<?php echo $db_field->id; ?>" title="Редактировать поле элемента структуры сайта"></a>
				<a class="icon2 icon_16x16 icon-delete delete-field-button" href="field/ajax_delete/<?php echo $db_structure->id ?>/<?php echo $db_field->id; ?>" title="Удалить поле элемента структуры сайта"></a>				
			</li>		  
			<?php endforeach?>	
		<?php endif ?> 
	</ul>	
	<input type="button" value="Добавить" class="icon2 icon_16x16 icon-add add-field-button" id="btnAddField" title="Добавить новое поле элементу структуры сайта">	
	<a class="clone-field-button" href="field/ajax_clone/<?php echo $db_structure->id ?>/" title="Редактировать поле элемента структуры сайта">Клонировать</a>
</form>
<!-- КОНЕЦ шаблона списка полей элемента структуры сайта -->
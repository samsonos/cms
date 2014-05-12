<?php 
// Переходники для работы с данными представления из контроллера
if( isset( $db_structure) ) $db_structure = & $db_structure;
?>

<div class="structure-element">
	<a class="open" href="structure/showtree/<?php echo $db_structure->id; ?>" title="Окрыть ветку в новом окне"><?php echo $db_structure->Name; ?></a>
	<div class="control-buttons">
		<a class="parent_id" style="display:none;"><?php echo $db_structure->ParentID; ?></a>
		<a class="structure_id" style="display:none;"><?php echo $db_structure->id ?></a>	
		<a class="control add icon2 icon_16x16 icon-add" href="<?php url_base('structure/form/',$db_structure->id)?>" title="Добавить подчинённый элемент структуры сайта"></a>
		<a class="control edit icon2 icon_16x16 icon-edit" href="<?php url_base('structure/form/',0, $db_structure->id) ?>" title="Редактировать данный ЭСС"></a>
		<a class="control delete icon2 icon_16x16 icon-delete" href="<?php url_base('structure/delete/', $db_structure->id, 'structure/tree')?>" title="Удалить данный ЭСС"></a>
		<a class="control move-up icon2 icon_16x16 icon-moveup" href="<?php url_base('structure/priority/',$db_structure->id, '1', 'structure/tree')?>" title="Переместить ЭСС выше по данной ветке"></a>
		<a class="control move-down icon2 icon_16x16 icon-movedown" href="<?php url_base('structure/priority/',$db_structure->id, '-1', 'structure/tree')?>" title="Переместить ЭСС ниже по данной ветке"></a>
		<a class="control fields icon2 icon_16x16 icon-add-field" href="<?php url_base('field/ajax_list/',$db_structure->id)?>" title="Управление полями ЭСС"></a>
		<a class="control permissions icon2 icon_16x16 icon-right" href="<?php url_base('permission/ajax_form/',$db_structure->id)?>/0" title="Управление правами для ЭСС"></a>
		<a class="control add-material icon2 icon_16x16 icon-add-material" href="<?php url_base('material/form/0/', $db_structure->id)?>" title="Добавить материал к данному ЭСС"></a>
		<a class="control materials icon2 icon_16x16 icon-material" href="<?php url_base('material/',$db_structure->id)?>" title="Перейти к материалам ЭСС"></a>
	</div>
</div>
<div class="clear"></div>
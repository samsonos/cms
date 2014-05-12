<?php if( isset( $db_rule )) $db_rule = & $db_rule; ?>
<!-- НАЧАЛО шаблона формы добавления права для сущности -->
<form method="post" class="form2" action="permission/rule_save/<?php v('object_id'); ?>" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
	<?php echo auth()->create_token( 'rule_form' ); ?>	
	<input type="hidden" name="GroupRightID" id="GroupRightID" value="<?php echo $db_rule->id; ?>">
	<input type="hidden" name="EntityKey" id="EntityKey" value="<?php v('key'); ?>">
	<input type="hidden" name="Active" id="Active" value="1">
	<?php if( ! isset( $db_rule ) ):?>		  
		<h2 class="header">Добавление прав для: <span class="bold"><?php v('object_name'); ?></span></h2>	
	<?php else:?>
		<h2 class="header">Редактирование правило # <span class="bold"><?php echo $db_rule->id; ?></span></h2>
	<?php endif?>	
	<ul class="table-like-list">
		<li title="Название права">
			<label>Право:</label>
			<select name="RightID" id="RightID" ><?php v('right_select');?></select>
		</li>
		<li title="Название группы">
			<label>Группа:</label>
			<select name="GroupID" id="GroupID" ><?php v('group_select');?></select>
		</li>
		<li title="Статус">
			<label>Тип:</label>
			<select name="Ban" id="Ban" ><?php v('status_select');?></select>
		</li>
	<?php if( ! isset( $db_rule ) ):?>
		<li title="Добавить новое поле" class="right-align"><input type="submit" value="Добавить" class=""></li>
	<?php else:?>
		<li title="Сохранить поле" class="right-align"><input type="submit" value="Сохранить" class=""></li>
	<?php endif?>	
	</ul>
</form>
<!-- КОНЕЦ шаблона формы добавления права для сущности -->
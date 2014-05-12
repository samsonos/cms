<!-- НАЧАЛО шаблона формы прав элемента структуры сайта -->
<form method="post" class="form2 permissions" action="field/save/<?php v('structure_id'); ?>/<?php v('permission_id'); ?>" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
	<input type="hidden" name="ObjectID" id="ObjectID" value="<?php v('structure_id'); ?>">
	<input type="hidden" name="ObjectType" id="ObjectType" value="<?php v('type'); ?>">	
	<h2 class="header">Управление правами для <span class="bold"><?php v('structure_name'); ?></span></h2>	 
	<table class="table">
		<thead>
			<tr>
				<th>№</th>				
				<th>Право</th>
				<th>Группа</th>
				<th>Статус</th>
				<th></th>
			</tr>
		</thead>
		<tbody><?php v('right_rows')?></tbody>
	</table>
	<ul class="table-like-list">		
		<li title="Сохранить права" class="right-align"><input type="button" id="btnAddRule" value="Добавить" class=""></li>
	</ul>
</form>
<!-- КОНЕЦ шаблона формы прав элемента структуры сайта -->
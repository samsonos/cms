<!-- Начало шаблона таблицы пользователей -->
<table id="list" class="table user-table"> 
	<thead>
		<tr>
			<th class="control"></th>
			<th class="author">Авторr</th>	
			<th class="email">Email</th>				
			<th class="text">Текст</th>
			<th class="material">Материал</th>
			<th class="moderate">Модерация</th>
			<th class="created">Создан / Изменен</th>							
		</tr>
	</thead>
	<tbody><?php v('comment_rows'); ?></tbody>
</table>
<!-- Конец шаблона таблицы пользователей -->
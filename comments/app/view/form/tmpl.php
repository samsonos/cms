<!-- НАЧАЛО шаблона формы пользователя сайта -->
<form method="post" class="form2" action="<?php module_url('ajax_save',$db_user->id) ?>" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>	
	<?php echo auth()->create_token( 'comment_form' ); ?>
	<input type="hidden" name="CommentID" id="CommentID" value="<?php v('comment_id'); ?>">
	<input type="hidden" name="Created" id="Created" value="<?php echo v('comment_Created'); ?>">
	<div class="header">Редактирование комментария</div>
	<ul class="table-like-list">
		<li><label>Текст:</label><div class="input-text"><textarea name="Text" id="Text"><?php v('comment_Text'); ?></textarea></div></li>
		<li title="Сохранить данные комментария" class="right-align"><input type="submit" value="Сохранить" class=""></li>	
	</ul>
</form>
<!-- КОНЕЦ шаблона формы пользователя сайта -->
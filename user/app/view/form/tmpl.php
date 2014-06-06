<?php 
// Переходники для работы с данными представления из контроллера
if( isset( $db_user) ) $db_user = & $db_user;
if( isset( $db_group) ) $db_group = & $db_group;
?>
<!-- НАЧАЛО шаблона формы пользователя сайта -->
<form method="post" class="form2" action="user/ajax_save/<?php v('user_id'); ?>" >
	<div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>	
	<?php echo auth()->create_token( 'user_form' ); ?>
	<input type="hidden" name="UserID" id="UserID" value="<?php v('user_id') ?>">
	<input type="hidden" name="Created" id="Created" value="<?php v('user_Created'); ?>">
	<?php if( ! isset( $db_user ) ):?>		  
		<div class="header"><?php t('Создание пользователя')?></div>
	<?php else:?>
		<div class="header"><?php t('Редактирование пользователя')?> <span class="bold"><?php echo mdl_user_short_name( $db_user ); ?></span></div>
	<?php endif?>		 
	<ul class="table-like-list">
		<li><label><?php t('Имя')?>:</label><div class="input-text"><input name="FName" id="FName" value="<?php v('user_FName'); ?>"></div></li>
		<li><label><?php t('Фамилия')?>:</label><div class="input-text"><input name="SName" id="SName" value="<?php v('user_SName'); ?>"></div></li>
		<li><label><?php t('Отчество')?>:</label><div class="input-text"><input name="TName" id="TName" value="<?php v('user_TName'); ?>"></div></li>
		<li><label>Email:</label><div class="input-text"><input name="Email" id="Email" value="<?php v('user_Email'); ?>"></div></li>
		<li><label>Password:</label><div class="input-text"><input type="password" name="Password" id="Password" value="<?php v('user_Password'); ?>"></div></li>
		
	<?php if( ! isset( $db_user ) ):?>
		<li title="<?php t('Добавить нового пользователя')?>" class="right-align"><input type="submit" value="<?php t('Добавить')?>" class=""></li>
	<?php else:?>
		<li title="<?php t('Сохранить данные пользователя')?>" class="right-align"><input type="submit" value="<?php t('Сохранить')?>" class=""></li>
	<?php endif?>	
	</ul>
</form>
<!-- КОНЕЦ шаблона формы пользователя сайта -->
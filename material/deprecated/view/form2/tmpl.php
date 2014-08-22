<!-- Шаблон формы редактирования материала -->
<?php if(isv('css')):?><style><?php v('css')?></style><?php endif?>
<h1 class="top-header">Редактирование материала #<?php v('cmsmaterial_id')?>: <span class="parent-name"><?php v('fixedName'); ?></span></h1>
<form id="material_editor" class="material-form" method="POST" action="<?php module_url('ajax_save');?>" enctype="multipart/form-data" accept-charset="UTF-8">

	<input name="iehack" type="hidden" value="&#9760;" />
	<input type="hidden" name="MaterialID" id="MaterialID" value="<?php v('cmsmaterial_id'); ?>">
	<input type="hidden" name="Draft" id="Draft" value="<?php v('cmsmaterial_Draft'); ?>">	
	<input type="hidden" name="UserID" id="UserID" value="<?php v('user_id'); ?>">
	<input type="hidden" name="Created" id="Created" value="<?php v('cmsmaterial_Created'); ?>">			
	<!-- Блок закладок формы -->
	<div id="material-tabs" class="samsonjstabs">	
		<ul class="tabs-list">
			<?php v('tabs_headers')?>			
		</ul>
		<?php v('tabs')?>		
	</div>	
	<!-- Начало: Блок управления формой -->
	<div class="btn-panel">			
		<input class="btn float-right last-right" type="button" id="btnSave" value="Сохранить" title="Сохранить материал в базе данных и закрыть форму">				
		<input class="btn float-right" type="button" id="btnApply" value="Применить" title="Сохранить материал в базе данных">
		<label class="btn float-right" title="Опубликовать материал на сайте для его отображения">Опубликовать <input type="checkbox" name="Published" id="btnPublished" <?php v('published'); ?>></label>
		<input class="btn float-right" type="button" id="btnClearHTML" value="Очистить HTML" title="Очистить текст материала от HTML">
		<input class="btn float-right" type="button" id="btnClearStyle" value="Очистить стили" title="Очистить текст материала от стилей в HTML">
		<input class="btn float-right" type="button" id="btnSwitchEditor" value="в HTML" title="">		
		<a class="btn float-right first-left" id="btnExit" href="<?php module_url()?>" title="Закрыть форму не сохраняя материал и вернуться к списку материалов">Закрыть</a>
		<div class="btn" id="btnCloneToLocale" title="Создать такой же материал в другой языковой версии сайта">
			Скопировать в:
			<select id="clone_locale">
				<?php if(locale()!=''):?><option value="ru">RU</option><?php endif?>
				<?php if(locale()!='ua'):?><option value="ua">UA</option><?php endif?>
				<?php if(locale()!='en'):?><option value="en">EN</option><?php endif?>				
			</select>
		</div>						
	</div>	
	<!-- Конец: Блок управления формой -->		
</form>
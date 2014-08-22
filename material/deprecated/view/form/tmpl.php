<!-- Шаблон формы редактирования материала -->
<?php if(isv('css')):?><style><?php v('css')?></style><?php endif?>
<h1 class="top-header">Редактирование материала #<?php v('cmsmaterial_id')?>: <span class="parent-name"><?php v('fixedName'); ?></span></h1>
<form id="material_editor" class="material-form material-form-old" method="POST" action="<?php module_url('ajax_save');?>" enctype="multipart/form-data" accept-charset="UTF-8">

	<input name="iehack" type="hidden" value="&#9760;" />
	<input type="hidden" name="MaterialID" id="MaterialID" value="<?php v('cmsmaterial_id'); ?>">
	<input type="hidden" name="Draft" id="Draft" value="<?php v('cmsmaterial_Draft'); ?>">	
	<input type="hidden" name="UserID" id="UserID" value="<?php v('user_id'); ?>">
	<input type="hidden" name="Created" id="Created" value="<?php v('cmsmaterial_Created'); ?>">			
	<!-- Блок закладок формы -->
	<div id="material-tabs" class="samsonjstabs">	
		<ul class="tabs-list">
			<li class="active"><div class="#main-tab">Основные</div></li>
			<li><div class="#content-tab">Текст</div></li>
			<li><div class="#teaser-tab">Тизер</div></li>					
			<li><div class="#seo-tab">SEO</div></li>				
			<?php if( isv('field_tab') ):?>
				<?php if( isv('field_tabs_name')):?>
				<li class="sub-tabs-list">
					<span>Дополнительные поля</span>
					<ul>
						<li><div class="#field-tab">Общие</div></li>				
						<?php v('field_tabs_name')?>
					</ul>
				</li>		
				<?php else:?>
					<li><div class="#field-tab">Дополнительные поля</div></li>
				<?php endif?>
			<?php endif ?>			
			<?php if( isv('gallery_tab') ):?><li><div class="#gallery-tab">Галерея</div></li><?php endif ?>	
			<?php if( isv('submaterial_tab') ):?><li><div class="#submaterial_tab">Подчиненные материалы</div></li><?php endif ?>			
		</ul>
		<div id="main-tab" class="tab-content">
			<div class="tab-inner ">		
				<ul class="table-like-list">
					<li><label>Название материала:</label><div class="input-text"><input id="name" name="Name" value="<?php vi('cmsmaterial_Name'); ?>"></div></li>				
					<li><label>URL(<a href="" class="create_url" title="Создать URL автоматически">Создать URL</a>):</label><div class="input-text"><input id="Url" name="Url" value="<?php v('cmsmaterial_Url'); ?>"></div></li>
					<li><label>Структура:</label><select multiple name="StructureID[]" id="StructureID"><?php v('parent_select') ?></select></li>
					<li><label>Автор:</label><div class="input-text disabled"><input id="user_name" name="user_name" value="<?php v('user_SName')?> <?php v('user_FName')?>" disabled></div></li>
				</ul>	
			</div>
		</div>
		<div id="content-tab" class="tab-content">
			<div class="tab-inner">
				<textarea id="Content" name="Content" cols="50" rows="15" ><?php vi('cmsmaterial_Content'); ?></textarea>
			</div>	
		</div>	
		<div id="teaser-tab" class="tab-content">
			<div class="tab-inner">
				<textarea id="Teaser" name="Teaser" cols="50" rows="5" ><?php vi('cmsmaterial_Teaser'); ?></textarea>
			</div>		
		</div>				
		<div id="seo-tab" class="tab-content">
			<div class="tab-inner">				
				<ul class="table-like-list">
					<li>
						<label>Заголовок страницы(Title):</label>
						<div class="input-text"><textarea class="mceNoEditor" id="Title" name="Title"><?php v('cmsmaterial_Title'); ?></textarea></div>
					</li>
					<li>
						<label>Описание(Description):</label>
						<div class="input-text"><textarea class="mceNoEditor" id="Description" name="Description"><?php v('cmsmaterial_Description'); ?></textarea></div>
					</li>	
					<li>
						<label>Ключевые слова(Keywords):</label>
						<div class="input-text"><textarea class="mceNoEditor" id="Keywords" name="Keywords"><?php v('cmsmaterial_Keywords'); ?></textarea></div>
					</li>
				</ul>
			</div>
		</div>				
		<?php v('gallery_tab')?>		
		<?php v('field_tab')?>
		<?php v('field_tabs')?>
		<?php v('submaterial_tab')?>		
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
<div id="main-tab" class="tab-content">
			<div class="tab-inner ">		
				<ul class="table-like-list">
					<li>
						<label>Название материала:</label>
						<div class="input-text"><input id="name" name="Name" value="<?php vi('cmsmaterial_Name'); ?>"></div>
					</li>				
					<li>
						<label>URL(<a href="" class="create_url" title="Создать URL автоматически">Создать URL</a>):</label>
						<div class="input-text"><input id="Url" name="Url" value="<?php v('cmsmaterial_Url'); ?>"></div>
					</li>
					<li>
						<label>Структура:</label>
						<select multiple name="StructureID[]" id="StructureID"><?php v('parent_select') ?></select>
					</li>	
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
					<li>
						<label>Автор:</label
						><div class="input-text disabled"><input id="user_name" name="user_name" value="<?php v('user_SName')?> <?php v('user_FName')?>" disabled></div></li>
					<li>
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
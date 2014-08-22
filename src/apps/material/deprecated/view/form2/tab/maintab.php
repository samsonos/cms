<ul class="table-like-list">
					<li>
						<label>Название материала:</label>
						<div class="input-text"><input id="name" name="Name" value="<?php vi('material_Name'); ?>"></div>
					</li>				
					<li>
						<label>URL(<a href="" class="create_url" title="Создать URL автоматически">Создать URL</a>):</label>
						<div class="input-text"><input id="Url" name="Url" value="<?php v('material_Url'); ?>"></div>
					</li>
					<li>
						<label>Структура:</label>
						<select multiple name="StructureID[]" id="StructureID"><?php v('parent_select') ?></select>
					</li>	
					<li>
						<label>Автор:</label
						><div class="input-text disabled"><input id="user_name" name="user_name" value="<?php v('user_SName')?> <?php v('user_FName')?>" disabled></div></li>
					<li>
				</ul>
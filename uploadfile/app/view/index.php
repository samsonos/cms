<div class="__inputfield __container __file_upload <?php v('emptyclass')?>" >	
	<input type="hidden" class="__action" value="<?php v('upload_controller')?>">
	<input type="hidden" class="__hidden" value="<?php v('field_value')?>">
	<input type="file" name="v" class="__input" value="" <?php if (!isv('field_value')):?>style="display:inline-block;"<?php endif;?>>
	<div class="__progress_bar"><p></p></div>
	<a  href="<?php v('delete_controller')?>" class="__delete icon2 icon_16x16 icon-delete delete" title="Удалить файл" <?php if (!isv('field_value')):?>style="display:none"<?php endif;?>></a>
	<span class="__file_name"><?php v('field_value')?></span>
</div>
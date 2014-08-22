<div class="__inputfield __container __file_upload <?php v('emptyclass')?>" >	
	<input type="hidden" class="__action" value="<?php v('upload_controller')?>">
	<input type="hidden" class="__hidden" value="<?php v('value')?>">
	<input type="file" name="v" class="__input" value="">
	<div class="__progress_bar"><p></p></div>
	<a  href="<?php v('delete_controller')?>" class="__delete icon2 icon_16x16 icon-delete delete" title="Удалить файл"></a>
	<span class="__file_name"><?php v('value')?></span>
</div>
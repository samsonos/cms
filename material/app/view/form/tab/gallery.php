<div id="gallery-tab" class="tab-content">
	<div class="tab-inner">
		<ul class="gallery">
		<?php if ( isset($gallery) ):?> 
			<?php foreach ( $gallery as $db_photo ):?>
				<li>	
					<a href="<?php url_base('gallery/ajax_delete/',$db_photo->id)?>" class="GalBtnDelete" title="Удалить фотографию">X</a>					 
					<img src="<?php echo '/'.$db_photo->Src; ?>">
					<a title="Редактировать фотографию" href="<?php url_base('gallery/ajax_editor/',$db_photo->id)?>" class="gallery-btn-edit">Редактировать</a>
					
				</li>
			<?php endforeach ?>	
		<?php endif ?>
		</ul>
		<a id="btnUploadPhoto" title="Загрузить новую фотографию в галлерею">Загрузить</a>
	<div class="clear"></div>												
	</div>
</div>
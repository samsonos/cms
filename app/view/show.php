<?php if( ! isset($location{0}) ) $location = 'http://' . $_SERVER['SERVER_NAME'] . ''; ?>
<!-- Фрейм для отображения содержания сайта -->
<iframe width="100%"  height="94%" frameBorder="0" src="<?php echo $location . '/?cms_editor=1'; ?>"></iframe>
<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 14:01
 */
?>

<div class="error-container">
    <h2><?php t('Ошибка') ?></h2>
    <?php iv('message') ?> <br>
    <?php if(url()->module == 'main') :?>
        <a href="<?php url_base('main','changepass') ?>"><?php t('Попробовать снова') ?></a>
    <?php endif ?>
</div>
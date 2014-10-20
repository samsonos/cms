<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 14:01
 */
?>

<div class="container">
    <div class="notification">
        <div class="notification__title"><?php t('Ошибка') ?></div>
        <div class="notification__content error">
            <?php iv('message') ?> <br>
            <?php if(url()->module == 'main') :?>
                <a href="<?php url_base('main','changepass') ?>"><?php t('Попробовать снова') ?></a>
            <?php endif ?>
        </div>
    </div>
</div>
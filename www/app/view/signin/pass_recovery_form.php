<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:53
 */
?>

<div class="container">
    <div class="form-container">
        <form class="signin-pages <?php iv('errorClass') ?>" method="post" action="<?php url_base('passrecovery', 'mail') ?>">
            <fieldset>
                <h2><?php t('Восстановление пароля') ?></h2>
                <input type="email" class="input-medium form-control" placeholder="E-mail" name="email" required>
                <br>
                <button type="submit" class="btn btn-signin"><?php t('Отправить') ?></button>
                <span class="help-block"><?php t('На введенный Вами email будет выслано сообщение с дальнейшими инструкциями.') ?></span>
            </fieldset>
        </form>
    </div>
</div>
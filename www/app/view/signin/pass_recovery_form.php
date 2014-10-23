<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:53
 */
?>

<div class="container">
    <form class="signin-pages" method="post" action="<?php url_base('passrecovery', 'mail') ?>">
        <fieldset>
            <h2><?php t('Восстановление пароля') ?></h2>
            <input type="email" class="input-medium form-control" placeholder="E-mail" name="email" required>
            <br>
            <input type="submit" class="btn btn-signin" value=<?php t('Отправить') ?>>
            <span class="help-block"><?php t('На введенный Вами email будет выслано сообщение с дальнейшими инструкциями.') ?></span>
        </fieldset>
    </form>
</div>
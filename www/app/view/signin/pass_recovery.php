<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:53
 */
?>

<div class="container">
    <div class="notification">
        <div class="notification__title">Восстановление пароля</div>
        <div class="recovery">
            <form method="post" action="<?php url_base('passrecovery', 'mail') ?>">
                <input type="email" class="input-form" placeholder="E-mail" name="email" required>
                <p>Убедитесь в правильности написания еmail</p>
                <input type="submit" class="btn" value="Восстановить пароль">
                <a class="recovery__back js-login-btn">Вернуться к форме входа</a>
                <div class="recovery__hint">
                    Для восстановления доступа, пожалуйста, введите полный адрес электронной почты (email), который вы используете для входа на портал. На указанный адрес будет выслано письмо с инструкцией по восстановлению пароля.
                </div>
            </form>
        </div>
    </div>
</div>
<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 22.10.2014
 * Time: 13:15
 */
?>

<div class="form-container">
    <form method="POST" action="<?php url_base('signin/login'); ?>" class="form-signin main-form recovery <?php iv('errorClass') ?>" role="form">
        <h2 class="form-signin-heading"><?php t('Авторизация') ?></h2>
        <input type="email" name="email" class="form-control" placeholder="<?php t('E-mail') ?>" value="<?php iv('userEmail') ?>" required="">
        <input type="password" name="password" class="form-control" placeholder="<?php t('Пароль') ?>" required="">
        <label class="checkbox">
            <input type="checkbox" value="remember-me"> <?php t('Запомнить меня') ?>
        </label>
        <div class="preloader_container"><div class="preloader"></div></div>
        <button class="btn btn-lg btn-signin btn-block" type="submit"><?php t('Войти') ?></button>
        <a href="<?php url_base('signin', 'passrecovery') ?>"><?php t('Восстановление пароля') ?></a>
    </form>
</div>

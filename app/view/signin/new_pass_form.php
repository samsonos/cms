<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:57
 */
?>

<div class="form-container">
    <form method="post" action="<?php url_base('signin', 'recovery', 'code') ?>" class="form-signin recovery" role="form">
        <h3 class="form-signin-heading"><?php t('Восстановление пароля') ?></h3>
        <input type="password" name="password" class="form-control" placeholder=<?php t('Новый пароль') ?>" required="" autofocus="">
        <input type="password" name="confirm_password" class="form-control" placeholder=<?php t('Еще раз новый пароль') ?>" required="">
        <button class="btn btn-lg btn-signin btn-block" type="submit"><?php t('Сохранить') ?></button>
    </form>
</div>
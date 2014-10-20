<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:57
 */
?>
<div class="container">
    <div class="notification">
        <div class="notification__title"><?php t('Использовать новый пароль') ?></div>
        <div class="recovery">
            <form method="post" action="<?php url_base('passrecovery', 'recovery', 'code') ?>">
                <div class="pass_recovery_form">
                    <div class="box">
                        <div class="pass_recovery_box" style="margin-left: 50px">
                            <?php t('Новый пароль') ?>
                            <input type="password" class="pass_recovery_input" name="password">
                            <br>
                            <?php t('Подтвердить пароль') ?>
                            <input type="password" class="pass_recovery_input" name="confirm_password">
                            <br>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn-upload" style="width: 110px" value="ГОТОВО">
                <div class="recovery__hint">
                    <?php t('Придумайте пароль, который не забудете.') ?>
                </div>
            </form>
        </div>
    </div>
</div>
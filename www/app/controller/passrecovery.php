<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:51
 */

function passrecovery()
{
    if (m('social')->authorized()) {
        e404();
    } else {
        m()->view('signin/pass_recovery')->title(t('Восстановление пароля', true));
    }
}

function passrecovery_mail()
{
    if (isset($_POST['email'])) {
        /** @var \samson\activerecord\user $user */
        $user = null;
        if (dbQuery('user')->Email($_POST['email'])->first($user)) {
            $user->confirmed = md5(generate_password(20).time());
            $user->save();
            $message = m()->view('signin/email/pass_recovery')->code($user->confirmed)->output();
            mail_send($user->email, 'nazarenko@samsonos.com', $message, t('Восстановление пароля!', true), 'CMS');
            m()->view('signin/pass_recovery_mailsend')->title(t('Восстановление пароля', true));
        } else {
            m()->view('signin/pass_error')->message(t('Пользователя с таким email адресом не существует. Проверьте Ваши данные или зарегистрируйтесь', true))->title(t('Ошибка восстановление пароля', true));
        }
    } else {
        e404();
    }
}

function passrecovery_confirm($code)
{
    if (dbQuery('user')->confirmed($code)->first()) {
        m()->view('signin/new_pass_form')->code($code)->title(t('Восстановление пароля', true));
    } else {
        e404();
    }
}

function passrecovery_recovery($code)
{
    if (isset($_POST['password']) && isset($_POST['confirm_password']) && $_POST['password'] == $_POST['confirm_password']) {
        /** @var \samson\activerecord\user $user */
        $user = null;
        if (dbQuery('user')->confirmed($code)->first($user)) {
            $user->confirmed = 1;
            $user->md5_pass = md5($_POST['password']);
            $user->save();
            url()->redirect();
        }
    } else {
        m()->view('registration/pass_error')->message(t('Вы вввели некорректный пароль либо пароли не совпадают', true))->title(t('Ошибка восстановления пароля', true));
    }
}
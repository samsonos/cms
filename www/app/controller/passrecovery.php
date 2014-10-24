<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 13:51
 */
use samson\social\email\EmailStatus;

function passrecovery()
{
    if (m('social')->authorized()) {
        e404();
    } else {
        $result = '';
        $result .= m()->view('signin/pass_recovery_form')->output();
        s()->template('app/view/signin/signin_template.php');
        m()->html($result)->title('Восстановление пароля');
    }
}

function passrecovery_mail()
{
    if (isset($_POST['email'])) {
        /** @var \samson\activerecord\user $user */
        $user = null;
        $result = '';
        if (dbQuery('user')->Email($_POST['email'])->first($user)) {
            $user->confirmed = md5(generate_password(20).time());
            $user->save();
            $message = m()->view('signin/email/pass_recovery')->code($user->confirmed)->output();

            mail_send($user->Email, 'info@samsonos.com', $message, t('Восстановление пароля!', true), 'SamsonCMS');

            $result .= m()->view('signin/pass_recovery_mailsend')->output();
            s()->template('app/view/signin/signin_template.php');
            m()->html($result)->title('Восстановление пароля');
        } else {
            url()->redirect();
        }
    } else {
        url()->redirect();
    }
}

function passrecovery_confirm($code)
{
    if (dbQuery('user')->confirmed($code)->first()) {
        $result = '';
        $result .= m()->view('signin/new_pass_form')->code($code)->output();
        s()->template('app/view/signin/signin_template.php');
        m()->html($result)->title('Восстановление пароля');
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
            $user->md5_password = md5($_POST['password']);
            $user->Password = $_POST['password'];
            $user->save();
            if (m('socialemail')->authorizeWithEmail($user->md5_email, $user->md5_password, $user)->code == EmailStatus::SUCCESS_EMAIL_AUTHORIZE) {
                url()->redirect();
            }
        }
    } else {
        $result = '';
        $result .= m()->view('signin/pass_error')->message(t('Вы ввели некорректный пароль либо пароли не совпадают', true))->output();
        s()->template('app/view/signin/signin_template.php');
        m()->html($result)->title('Ошибка восстановление пароля');
    }
}
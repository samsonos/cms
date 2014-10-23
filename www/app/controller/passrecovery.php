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
//        s()->template('app/view/signin/pass_recovery_form.php');
//        m()->title(t('Восстановление пароля', true));

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
            mail_send($user->Email, $user->Email, $message, t('Восстановление пароля!', true), 'SamsonCMS');
            //m()->view('signin/pass_recovery_mailsend')->title(t('Восстановление пароля', true));

            $result .= m()->view('signin/pass_recovery_mailsend')->output();
            s()->template('app/view/signin/signin_template.php');
            m()->html($result)->title('Восстановление пароля');
        } else {
            //m()->view('signin/pass_error')->message(t('Пользователя с таким email адресом не существует. Проверьте Ваши данные или зарегистрируйтесь', true))->title(t('Ошибка восстановление пароля', true));

            $result .= m()->view('signin/pass_error')->message(t('Пользователя с таким email адресом не существует. Проверьте Ваши данные или зарегистрируйтесь.', true))->output();
            s()->template('app/view/signin/signin_template.php');
            m()->html($result)->title('Ошибка восстановление пароля');
        }
    } else {
        e404();
    }
}

function passrecovery_confirm($code)
{
    if (dbQuery('user')->confirmed($code)->first()) {
        //m()->view('signin/new_pass_form')->code($code)->title(t('Восстановление пароля', true));
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
            url()->redirect();
        }
    } else {
        //m()->view('registration/pass_error')->message(t('Вы вввели некорректный пароль либо пароли не совпадают', true))->title(t('Ошибка восстановления пароля', true));
        $result = '';
        $result .= m()->view('signin/pass_error')->message(t('Вы ввели некорректный пароль либо пароли не совпадают', true))->output();
        s()->template('app/view/signin/signin_template.php');
        m()->html($result)->title('Ошибка восстановление пароля');
    }
}
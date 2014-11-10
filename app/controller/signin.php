<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 11:43
 */
use samson\social\email\EmailStatus;

/** Check the user's authorization */
function signin__HANDLER()
{
    if (!m('social')->authorized()) {
        if (!url()->is('signin') && !url()->is('passrecovery')) {
            url()->redirect('signin');
        }
    }
}

/** Main sign in template */
function signin()
{
    s()->template('app/view/signin/signin_template.php');
    $result = '';
    $result .= m()->view('signin/signin_form.php')->output();
    m()->html($result)->title('Авторизация');
}

/** User asynchronous sign in */
function signin_async_login()
{
    $user = null;
    $error = '';
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = md5($_POST['email']);
        $password = md5($_POST['password']);
        $auth = m('socialemail')->authorizeWithEmail($email, $password, $user);
        if ($auth->code == EmailStatus::SUCCESS_EMAIL_AUTHORIZE) {
            if (dbQuery('user')->cond('UserID', $user->id)->first()) {
                return array('status' => '1');
            } else {
                $error .= m()->view('signin/signin_form.php')->errorClass('errorAuth')->output();
                return array('status' => '0', 'html' => $error);
            }
        } else {
            $error .= m()->view('signin/signin_form.php')->errorClass('errorAuth')->userEmail("{$_POST['email']}")->output();
            return array('status' => '0', 'html' => $error);
        }
    } else {
        $error .= m()->view('signin/signin_form')->errorClass('errorAuth')->output();
        return array('status' => '0', 'html' => $error);
    }
}

/** User logout */
function signin_logout()
{
    m('socialemail')->deauthorize();
    url()->redirect();
}

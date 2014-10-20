<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 11:43
 */
use samson\social\email\EmailStatus;

function signin(){
    s()->template('app/view/signin/signin.php');
    m()->title('Авторизация');
}

function signin_login(){
    $user = null;
    if (isset($_POST['email']) && ($_POST['email'] != '') && isset($_POST['password']) && ($_POST['password'] != '')) {
        if (m('socialemail')->authorizeWithEmail(md5($_POST['email']), md5($_POST['password']), $user)->code == EmailStatus::SUCCESS_EMAIL_AUTHORIZE) {
            if (dbQuery('user')->cond('UserID', $user->id)->first()) {
                url()->redirect('main');
            } else {
                url()->redirect();
            }
        } else {
            url()->redirect('signin/error');
        }
    } else {
        url()->redirect('signin/error');
    }
}

function signin_error()
{
    s()->template('app/view/signin/login_error.php');
    m()->title('Ошибка');
}

function signin_logout()
{
    m('socialemail')->deauthorize();
    url()->redirect();
}

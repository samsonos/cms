<?php
/**
 * Created by PhpStorm.
 * User: nazarenko
 * Date: 20.10.2014
 * Time: 11:43
 */
use samson\social\email\EmailStatus;

function signin(){
//    s()->template('app/view/signin/signin_template.php');
//    m()->title('Авторизация');

    s()->template('app/view/signin/signin_template.php');
    $result = '';
    $result .= m()->view('signin/signin_form.php')->output();
    m()->html($result)->title('Авторизация');
}

function signin_login(){
    $user = null;
    $error = '';
    if (isset($_POST['email']) && ($_POST['email'] != '') && isset($_POST['password']) && ($_POST['password'] != '')) {
        if (m('socialemail')->authorizeWithEmail(md5($_POST['email']), md5($_POST['password']), $user)->code == EmailStatus::SUCCESS_EMAIL_AUTHORIZE) {
            if (dbQuery('user')->cond('UserID', $user->id)->first()) {
                url()->redirect('');
            } else {
                s()->template('app/view/signin/signin_template.php');
                $error .= m()->view('signin/signin_form.php')->errorClass('errorAuth')->output();
                m()->html($error)->title('Авторизация');
            }
        } else {
            s()->template('app/view/signin/signin_template.php');
            $error .= m()->view('signin/signin_form.php')->errorClass('errorAuth')->userEmail("{$_POST['email']}")->output();
            m()->html($error)->title('Авторизация');
        }
    } else {
        s()->template('app/view/signin/signin_template.php');
        $error .= m()->view('signin/signin_form')->errorClass('errorAuth')->output();
        m()->html($error)->title('Авторизация');
    }
}

//function signin_error()
//{
////    s()->template('app/view/signin/login_error.php');
////    m()->title('Ошибка');
//
//    s()->template('app/view/signin/signin_template.php');
//    $result = '';
//    $result .= m()->view('signin/login_error')->output();
//    m()->html($result)->title('Ошибка');
//}

function signin_logout()
{
    m('socialemail')->deauthorize();
    url()->redirect();
}

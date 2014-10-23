<!DOCTYPE html>
<html>

<head>
    <title><?php v('title'); ?> - SamsonCMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="ru" />
    <link rel="icon" type="image/png" href="favicon.png" >
</head>

<body id="<?php v('id'); ?>">

<div class="container">
    <form method="post" action="<?php url_base('signin/login'); ?>" class="form-signin" role="form">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="email" name="email" class="form-control" placeholder="Email address" required="" autofocus="">
        <input type="password" name="password" class="form-control" placeholder="Password" required="">
        <label class="checkbox">
            <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <a href="<?php url_base('passrecovery') ?>"><?php t('Восстановление пароля') ?></a>
    </form>
</div>

</body>
</html>
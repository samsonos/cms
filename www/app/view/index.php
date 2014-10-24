<?php
// Проверка, авторизирован ли пользователь
// Если нет, перенаправляем на страницу входа
if (!m('social')->authorized()) {
    url()->redirect('signin');
}
?>

<!DOCTYPE html>

<html>
<head>
    <title><?php v('title'); ?> - SamsonCMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="ru" />
    <link rel="icon" type="image/png" href="favicon.png" >
</head>
<body id="<?php v('id'); ?>">
<header><?php m('menu')->render(''); ?></header>
<section id="data">
    <?php m()->render() ?>
</section>
</body>
</html>
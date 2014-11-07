<!DOCTYPE html>
<html>

<head>
    <title><?php v('title'); ?> - SamsonCMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="ru" />
    <link rel="icon" type="image/png" href="favicon.png" >
</head>

<body id="<?php v('id'); ?>" class="signin">

<?php m('i18n')->render('list')?>

<div class="container">
    <?php m()->render() ?>
</div>

</body>
</html>
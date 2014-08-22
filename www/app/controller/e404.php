<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 22.08.14 at 17:20
 */
function e404()
{
    m()->view('e404')->title(t('Страница не найдена', true));
}

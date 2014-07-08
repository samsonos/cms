<?
/**
 * SamsonCMS v 4.0.0
 * 
 * Универсальный пусковой скрипт для запуска SamsonCMS
 * Скрипт написан с испозованием поддержкой удалённого запуска
 * что даёт возможность использовать однк копию данного приложении
 * для нескольких веб-сайтов. 
 * 
 * @author Vitaly Iegorov <vitalyiegorov@gmail.com>  
 */
//asdasdasd

/** Конфигурация для Auth */
class AuthCMSConfig extends samson\core\Config
{
	public $__module = 'auth2';
	public $entity 	= 'user';
	public $force = true;
}

// Set supported locales
setlocales('en', 'ru');

// Запуска ядра SamsonPHP
s()->composer()->start('main');

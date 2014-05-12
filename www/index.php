<?
/**
 * SamsonCMS v 3.0.5
 * 
 * Универсальный пусковой скрипт для запуска SamsonCMS
 * Скрипт написан с испозованием поддержкой удалённого запуска
 * что даёт возможность использовать однк копию данного приложении
 * для нескольких веб-сайтов. 
 * 
 * @author Vitaly Iegorov <vitalyiegorov@gmail.com>  
 */

// Define path to SamsonPHP modules
if( !defined('PHP_P')) define('PHP_P', 'E:/SamsonPHP/');
// Define path to SamsonJS modules
if( !defined('JS_P')) define('JS_P', 'E:/SamsonJS/');
// Define path to SamsonCMS modules
if( !defined('CMS_P')) define('CMS_P', 'E:/SamsonCMS/');

/** Конфигурация для Auth */
class AuthCMSConfig extends samson\core\Config
{
	public $__module = 'auth2';
	public $entity 	= 'user';
	public $force = true;
}

// Запуска ядра SamsonPHP
s()
	->load( PHP_P.'resourcer')		// Подключим модуль Управления ресурсами
	->load( PHP_P.'activerecord' )	// Загрузим модуль для работы с БД			
	->load( PHP_P.'less')			// Подключим модуль LESS
	->load( PHP_P.'minify')			// Подключим модуль минификации ресурсов		
	->load( PHP_P.'compressor')		// Подключим модуль Сворачиватель сайта			
	->load( JS_P.'core' ) 			// Загрузим модуль SamsonJS		
	->load( JS_P.'formcontainer' ) 	// Загрузим модуль SamsonJS: SJSFormContainer
	->load( JS_P.'tabs/' ) 			// Загрузим модуль SamsonJS: SJSTabs
	->load( JS_P.'translit/' ) 		// Загрузим модуль SamsonJS: SJSTranslit
	->load( JS_P.'gallery/' ) 		// Загрузим модуль SamsonJS: SJSGallery
	->load( JS_P.'md5' ) 			// Загрузим модуль SamsonJS: SJSMD5
	->load( JS_P.'treeview/')		// Загрузим модуль SamsonJS: SJSTreeView
	->load( JS_P.'fixedheader/')	// Загрузим модуль SamsonJS: SJSTreeView
	->load( JS_P.'lightbox/') 		// Загрузим модуль SamsonJS: LightBox
	->load( JS_P.'tinybox/') 		// Загрузим модуль SamsonJS: LightBox
	->load( JS_P.'autocomplete/') 	// Загрузим модуль SamsonJS: Autocomplete
	->load( JS_P.'select/') 		// Загрузим модуль SamsonJS: Autocomplete	
	->load( PHP_P.'i18n' )			// Загрузим модуль "Универсальный контроллер"
	->load( PHP_P.'unitable' )		// Загрузим модуль "Таблицы"		
	->load( PHP_P.'pager')			// Загрузим модуль для постраничного вывода
	->load( PHP_P.'forms')			// Load element generator
	->load( PHP_P.'deploy')			// Load deploy module
	->load( PHP_P.'upload')	
	->load( PHP_P.'scale')
//	->load( PHP_P.'udbc')
    ->load( PHP_P.'email')
	//->load( VEN_P.'ckeditor' ) 	// Загрузим модуль "CKEditor" WYSIWYG
	->load( CMS_P.'app')			// Загрузим модуль SamsonCMS Application
	->load( CMS_P.'api')			// Загрузим модуль SamsonCMS API	
	->load( CMS_P.'inputfield')		// Загрузим модуль SamsonCMS Input field
	->load( CMS_P.'select')		    // Загрузим модуль SamsonCMS Input field
	->load( CMS_P.'uploadfile')
	->load( CMS_P.'date')
	->load( CMS_P.'wysiwyg')
	->load( CMS_P.'ajaxloader')		// Загрузим модуль SamsonCMS AjaxLoader
	->load( CMS_P.'table')			// Загрузим модуль SamsonCMS Table
	->load( CMS_P.'navigation' )	// Загрузим модуль "Навигация"
	->load( CMS_P.'field' )			// Загрузим модуль "Доп. поля"
	->load( CMS_P.'material' )		// Загрузим модуль "Материалы"
	->load( CMS_P.'gallery' )		// Загрузим модуль "Галлерея"
	->load( CMS_P.'user' )			// Загрузим модуль "Пользователь"
	->load( CMS_P.'help' )			// Загрузим модуль "Помощь"	
	->load( PHP_P.'parse')		    // Подключим модуль Сворачиватель сайта
    ->load( PHP_P.'auth' ) 			// Загрузим модуль "Авторизация", с принудительной авторизацией	
	->start('main');				// Запустим ядро системы
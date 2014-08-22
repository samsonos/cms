<?php
namespace samson\cms\web\user;

/**
 * Class UserApplication
 * @package samson\cms\web\user
 */
class UserApplication extends \samson\cms\App
{
	/** @var string Application name */
	public $app_name = 'Пользователи';
	
	/** @var bool Hide application access from main menu */
	public $hide =  false;

	/** @var string Module identifier */
	protected $id = 'user';

    /**
     * Universal controller
     */
    public function __HANDLER()
    {
        $query = dbQuery('user')->Active(1);

        $table = new samson\cms\web\user\Table($query);

        // Установим представление
        m()->view('index')->title(t('Пользователи системы', true))
            // Установим шаблон таблицы пользователей
            ->set( 'user_table',$table->render() );
    }
}

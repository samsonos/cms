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

	/** @var string Module identifier */
	protected $id = 'user';

    /**
     * Universal controller
     */
    public function __HANDLER()
    {
        // Create query to get users
        $query = dbQuery('user')->Active(1)->order_by('UserID');

        // Create generic table for users
        $table = new Table($query);

        // Установим представление
        m()->view('main/index')->title(t('Пользователи системы', true))
            // Установим шаблон таблицы пользователей
            ->user_table($table->render());
    }

    /** Save user data */
    public function __ajax_save()
    {
        // If form has been sent
        if (isset($_POST)) {

            // Create or find user depending on UserID passed
            /** @var \samson\activerecord\user $db_user */
            $db_user = null;
            if (!dbQuery('user')->UserID($_POST['UserID'])->Active(1)->first($db_user)) {
                $db_user = new \samson\activerecord\user(false);
            }

            // Save user data from form
            $db_user->Created 		= ( $_POST['Created'] == 0 ) ? date('Y-m-d H:i:s') : $_POST['Created'];
            $db_user->md5_password 	= md5($_POST['Password']);
            $db_user->md5_email 	= md5($_POST['Email']);
            $db_user->Active		= 1;
            $db_user->save();

            // Refresh session user object
            auth()->update($db_user);
        }
    }
}

<?php 
namespace samson\cms\web;
use samson\cms\CMS;

/**
 * SamsonCMS Structure application module
 * Mainly interacts with "structure", "structurefield" database tables
 * @author Vitaly Egorov <egorov@samsonos.com>
 */

class StructureApplication extends \samson\cms\App
{
	/** Application name */	
	public $name = 'Структура';
	
	/** Identifier */
	protected $id = 'structure';	
	
	/** Dependencies */
	protected $requirements = array( 'activerecord', 'cmsapi');	
	
	/** @see \samson\cms\App::main() */
	public function main()
	{
		// Get new structures
		if (dbQuery('samson\cms\cmsnav')->join('user')->Active(1)->order_by('Created','DESC')->limit(5)->exec($db_navs))
		{
			// Render material rows
			$rows_html = '';
			foreach ( $db_navs as $db_nav ) $rows_html .= $this->view('main/row')
			->nav($db_nav)
			->user($db_nav->onetoone['_user'])			
			->output();		

			for ($i = sizeof($db_navs); $i < 5; $i++) 
			{
				$rows_html .= $this->view('main/row')->output();
			}
			// Render main template
			return $this->rows($rows_html)->output('main/index');
		}		
	}

    /**
     * контроллер по умолчанию
     * @param null $structure_id
     */
    public function __HANDLER()
    {
        // Установим дерево ЭСС
        m()->view('2.0/index')
            ->title('Элементы структуры содержания сайта!')
            ->tree(CMSNav::fullTree());
    }
    public function __async_showall() {

        $html = m()->view('2.0/index')
            ->title('Элементы структуры содержания сайта')
            ->tree(CMSNav::fullTree())
            ->output();
        return array(
            'status'=>1,
            'tree'=>$html
        );
    }
    public function create_select($parentID = 0)
    {
        $select = '';
        $data = null;
        $mewdata = null;
        if (dbQuery('\samson\cms\web\CMSNav')->StructureID($parentID)->first($data)) {
            // New cmsnav
            $select .= '<option title="'.$data->Name.'" selected value="'.$data->id.'">'.$data->Name.'</option>';
        } else {
            $select .= '<option title="Не выбрано" value="Не выбрано">Не выбрано</option>';
        }
        if (dbQuery('\samson\cms\web\CMSNav')->exec($newdata)) {
            foreach ($newdata as $key=>$val) {
                $select .= '<option title="'.$val->Name.'" value="'.$val->id.'">'.$val->Name.'</option>';
            }
        }
        return $select;
    }
    /**
     * асинхронное создание формы для создания/редактирования структуры
     * @param null $parentID
     * @param int $navID
     * @return array
     */
    public function __async_form($parentID = NULL, $navID = 0)
    {
        $data = null;

        if (!dbQuery('\samson\cms\web\CMSNav')->StructureID($navID)->first($data)) {
            // New cmsnav
        }

        // Render form
        $html = m()->view('2.0/form/form')
            ->parent_select($this->create_select($parentID))
            ->cmsnav($data)
            ->output();

        return array(
            'status'=>1,
            'html'=>$html
        );
    }

    /**
     * Построение нового дерева. (Вызывается в цепи контроллеров)
     * @param null $parentCMSNav - указатель на родителя
     *
     * @return array
     */
    public function __async_tree($parentCMSNav = null)
    {
        $tree = '';
        $nav = null;
        $html = '';
        $data = null;
        //$parentCMSNav = & CMSNav::$top;
        /*if (!ifcmsnav($parentCMSNav, $parentCMSNav)) {
            $parentCMSNav = & CMSNav::$top;
        }*/
        //CMSNav::fullTree();
        //$tree = $parentCMSNav->htmlTree($html, m()->path().__SAMSON_VIEW_PATH.'tree.element.tmpl.php');
        /*if (dbQuery('\samson\cms\web\CMSNav')->StructureID(131)->first($data)) {
            //$parentCMSNav = $data->
            CMSNav::fullTree($data);
            $tree = $data->htmlTree($html, m()->path().__SAMSON_VIEW_PATH.'tree.element.tmpl.php');
        }*/
        //CMSNav::fullTree();
        //m('cmsapi')->buildNavigation();
        //$tree = $parentCMSNav->toHTML($nav,$html, m()->path().__SAMSON_VIEW_PATH.'tree.element.tmpl.php');
        $tree = CMSNav::fullTree();
        return array(
            'status' => 1,
            'tree' => $tree
        );

    }

    /**
     * Saving or creating new structure
     * @param int $navID structure identifier
     * @return array Ajax response
     */
    public function __async_save($navID = 0)
    {
        /** @var \samson\cms\web\CMSNav $data */
        $data = null;

        if (dbQuery('\samson\cms\web\CMSNav')->StructureID($navID)->first($data)) {
            // Update structure data
            $data->update();
        } else {
            // Create new structure
            $nav = new \samson\cms\web\CMSNav(false);
            $nav->fillFields();
        }

        // return Ajax response
        return array('status'=>1);
    }

    /**
     * @param int $navID - идентификатор структуры
     *
     * удаление выбранной структуры из таблицы
     *
     * @return array
     */

    public function __async_delete($navID = 0)
    {
        $data = null;
        $response = array ('status'=>0);

        if (dbQuery('\samson\cms\web\CMSNav')->StructureID($navID)->first($data)) {
            $data->delete(); //удаляем структуру
            $response['status'] = 1;
        }

        return $response;
    }

    /**
     * Изменение порядкового номера структуры в дереве
     *
     * @param int $navID селектор-ИД структуры
     * @param     $direction - вид изменения
     *
     * @return array - AJAX - response
     */
    public function __async_priority($navID = 0, $direction)
    {
        $data = null;
        $response = array ('status'=>0);

        if (ifcmsnav($navID, $data, 'id')) {
            $data->priority($direction);
            $response['status'] = 1;
        }
        //m('cmsapi')->buildNavigation();

        return $response;
    }

    public function __async_showtree($structure_id = null)
    {
        $db_structure = null;
        // Проверим есть ли элемент структуры с таким ИД
        if (dbQuery('\samson\cms\web\CMSNav')->StructureID($structure_id)->first($db_structure)) {}
        $html = m()->view('2.0/index')
            ->title('Элементы структуры содержания сайта')
            ->parent($db_structure)
            ->tree(CMSNav::fullTree($db_structure))
            ->output();

        $sub_menu = m()->view('2.0/main/sub_menu')->parentnav_id($structure_id)->output();

        return array(
            'status'=>1,
            'tree'=>$html,
            'sub_menu' => $sub_menu
        );
    }
}
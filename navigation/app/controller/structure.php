<?php
use samson\cms\CMSNav;
use samson\cms\CMSMaterial;

/**
 * Ассинхронный контроллер для сохранения/создания элемента структуры сайта
 */
/*function structure_save(){
	udbc()->ajax_action( 'mdl_structure_save', array( $_POST ), 'structure_update_handler' );
}*/

/**
 * Ассинхронный контроллер для удаления элемента структуры сайта
 */
/*function structure_delete(){
	udbc()->ajax_action( 'mdl_structure_delete', array( $_POST ), 'structure_update_handler' );
}*/

/**
 * Ассинхронный обработчик изменения положения ЭСС в ёё ветке
 *
 * @param string $structure_id 	Идентификатор ЭСС
 * @param string $direction 	Направление перемещения ЭСС
 * @return string Дерево ЭСС
 */
/*function structure_priority( $structure_id, $direction ){
	udbc()->ajax_action( 'mdl_structure_priority', array( $structure_id, $direction ), 'structure_update_handler' );
}*/

/**
 * Универсальный контроллер ЭСС
 *
 * @param string $structure_id Идентификатор ЭСС в БД
 */
/*function structure__HANDLER( $structure_id = NULL )
{
	// Проверим есть ли элемент структуры с таким ИД
	if ( ifcmsnav( $structure_id, $db_structure, 'id') )
	{
		// Установим заголовок
		m()->title( 'Структура сайта для '.$db_structure->Name );

		// Установим идентификатор ЭСС для подменю
		m()->view('sub_menu')->parentnav( $db_structure );
	}
	// Создадим пустышку
	else $db_structure = CMSNav::$top;

	// Установим дерево ЭСС
	m()->view('2.0/index')
	->title('Элементы структуры содержания сайта')
	->parent( $db_structure != CMSNav::$top ? $db_structure : '' )
	->tree( mdl_structure_html_tree( $db_structure ));
}*/

/**
 * Контроллер для вывода формы удаления ЭСС
 * @param string $structure_id Идентификатор ЭСС для удаления
 */
/*function structure_form_delete( $structure_id )
{
	// Уберем вывод шаблона - АЯКС ЕБТЯ
	s()->async(TRUE);

	// Загрузим форму для удаления, если нам передан существующий ID ЭСС - передадим в форму ЭСС
	if( ifcmsnav( $structure_id, $db_structure, 'id')) echo m()->set( $db_structure )->output('app/view/delete.php');
}*/

/**
 * Ассинхронный контроллер формы создания/редактирования
 *
 * @param string $parentID  Идентификатор родительского элемента
 * @param string $navID     Идентификатор элемента структуры сайта
 */
/*function structure_form( $parentID = NULL, $navID = NULL )
{
    // Уберем вывод шаблона - АЯКС ЕБТЯ
    s()->async(TRUE);

    // Опредилим текущий ЭСС - иначе создадим пустышку
    $nav = null;
    if (!ifcmsnav($navID, $nav, 'id')) {
        $nav = new CMSNav(false);
    }

    // Опредилим родительский ЭСС
    $parent = null;
    if (!ifcmsnav($parentID, $parent, 'id')) {
        $parent = new CMSNav(false);
    }

    // Опредилим материал по умолчанию ЭСС
    $material = null;
    if (!ifcmsmat( $nav->MaterialID, $material, 'id')) {
        $material = new CMSMaterial(false);
    }

    // Установим переменные для представления
    echo m()
        ->set($nav)
        ->set($material)
        ->db_parent($parent)
        ->parent_select(html_db_form_select_options('samson\cms\cmsnav', $parentID))
        ->output('app/view/form.php');
}*/

/**
 * Обработчик сохранения изменений в ЭСС
 * @return string Дерево ЭСС
 */
function structure_update_handler()
{
	// Построим дерево навигации заново
	cms()->buildNavigation();

	// Вернем дерево ЭСС
	return mdl_structure_html_tree();
}

/**
* Ассинхронное получние списка эсс
*/
function structure_ajax_autocomplete()
{
	s()->async(TRUE);

	echo json_encode( mdl_structure_autocomplete( $_POST['_data'] ) );
}
?>
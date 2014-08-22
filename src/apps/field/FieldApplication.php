<?php 
namespace samson\cms\web\field;

class FieldApplication extends \samson\cms\App
{
    public $app_name = 'Доп. поля';

    protected $id = 'field';

    /** Hide application access from main menu */
    /*public $hide = true;*/

    protected $requirements = array
    (
        'ActiveRecord'
    );

    /**
     * Default module handler
     * @param int $nav Current Structure identifier
     * @param int $page Current page
     */
    public function __HANDLER($nav = 0, $page = 1)
    {
        // Render view
        m()->view('index')
            ->html(CMSField::renderTable($nav, $page))
            ->title('Дополнительные поля');
    }

    /**
     * Creating list of structures
     * @param int $structure_id Current structure identifier
     *
     * @return array Ajax response
     */
    public function __async_list($structure_id)
    {
        /** @var array $return Ajax response array */
        $return = array('status' => 0);

        /** @var \samson\cms\web\CMSNav $cmsNav */
        $cmsNav = null;

        // If exists current structure
        if (dbQuery('\samson\cms\web\CMSNav')->id($structure_id)->first($cmsNav)) {

            // Create view of list
            $html = m()->view('form/field_list')->structure($cmsNav)->items($cmsNav->getFieldList())->output();

            // Set positive Ajax status
            $return['status'] = 1;

            // Set view
            $return['html'] = $html;
        }

        // Return Ajax response
        return $return;
    }

    /**
     * Create form for adding or updating additional field
     * @param int  $structure_id Current structure identifier
     * @param null $field_id Current field identifier
     *
     * @return array Ajax response
     */
    public function __async_form($structure_id, $field_id = null)
    {
        /** @var array $return Ajax response array */
        $return = array('status' => 0, 'html' => '');

        // If exists current structure
        if (ifcmsnav($structure_id, $cmsNav, 'id')) {
            // Set default field type
            $type = 0;

            // Add structure to view
            m()->set($cmsNav);
        }

        // If exists current field
        if (dbQuery('field')->id($field_id)->first($cmsField)) {
            // Get type of field
            $type = $cmsField->Type;

            // Add field to view
            m()->set($cmsField);
        }

        // Set Ajax status 1
        $return['status'] = 1;

        // Add select form to view
        m()->type_select(CMSField::createSelect($type));

        // Create view
        $html = m()->output('form/form');

        $return['html'] = $html;

        // Return Ajax response
        return $return;
    }

    /**
     * Save information about field or create new field
     * @param int  $structure_id Current structure identifier
     * @param null $field_id Current field identifier
     *
     * @return array Ajax response
     */
    public function __async_save($structure_id, $field_id = null)
    {
        // If not exists current field
        if (!dbQuery('\samson\cms\field\CMSField')->id($field_id)->first($field)) {
            // Create new field
            $field = new CMSField(false);
        }

        // Update field data
        $field->update($structure_id);

        // Return positive Ajax status
        return array('status' => 1);
    }

    /**
     * Delete relation between structure and field
     * @param int  $structure_id Current structure identifier
     * @param int $field_id Current field identifier
     *
     * @return array Ajax response
     */
    public function __async_deleterelation($structure_id, $field_id)
    {
        /** @var \samson\cms\CMSNavField $relation */
        if (dbQuery('\samson\cms\CMSNavField')->FieldID($field_id)->StructureID($structure_id)->first($relation)) {
            // Delete relation
            $relation->delete();
        }

        // Return positive Ajax status
        return array('status' => 1);
    }

    /**
     * Render additional field list of current structure
     * @param int  $structure_id Current structure identifier
     *
     * @return array Ajax response
     */
    public function __async_renderfields($structure_id)
    {
        /** @var \samson\cms\web\CMSNav $cmsNav */
        $cmsNav = dbQuery('\samson\cms\web\CMSNav')->id($structure_id)->first();

        // Return Ajax response
        return array('status' => 1, 'fields' => $cmsNav->getFieldList());
    }

    public function __async_delete($field_id) {
        // If exists current field then delete it
        if (dbQuery('field')->id($field_id)->first($field)) {
            $field->delete();

            /** @var array $matRelations array of materialfield relations */
            $matRelations = null;

            // Delete all relations between current field and materials
            if (dbQuery('materialfield')->FieldID($field_id)->exec($matRelations)) {
                foreach ($matRelations as $matRelation) {
                    $matRelation->delete();
                }
            }

            /** @var array $strRelations array of structurefield relations */
            $strRelations = null;

            // Delete all relations between current field and structures
            if (dbQuery('structurefield')->FieldID($field_id)->exec($strRelations)) {
                foreach ($strRelations as $strRelation) {
                    $strRelation->delete();
                }
            }
        }

        // Return positive Ajax status
        return array('status' => 1);
    }

    public function __async_updatetable($nav_id = null, $page = 1)
    {
        /** @var \samson\pager\Pager $pager */
        $pager = null;
        // Return Ajax response
        return array('status' => 1, 'table' => CMSField::renderTable($nav_id, $page, $pager), 'pager' => $pager->toHTML());
    }
}

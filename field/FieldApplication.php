<?php 
namespace samson\cms\field;

class FieldApplication extends \samson\cms\App
{
    public $app_name = 'Доп. поля';

    protected $id = 'field';

    /** Hide application access from main menu */
    public $hide = true;

    protected $requirements = array
    (
        'ActiveRecord'
    );

    public function __async_list($structure_id)
    {
        $return = array('status' => 0);
        // Попытаемся найти ЭНС
        if (ifcmsnav($structure_id, $db_structure, 'id')) {
            $fields = dbQuery('\samson\cms\field\CMSField')
                    ->join('\samson\cms\CMSNavField')
                    ->cond('StructureID', $structure_id)
                ->exec();
            $items = '';
            if (sizeof($fields)) {
                foreach ($fields as $field) {
                    $items .= m()->view('form/field_item')->field($field)->structure($db_structure)->output();
                }
            } else {
                $items = m()->view('form/empty_field')->output();
            }

            $html = m()->view('form/field_list')->structure($db_structure)->items($items)->output();

            $return['status'] = 1;
            $return['html'] = $html;
        }

        return $return;
    }

    public function __async_form($structure_id, $field_id = null)
    {
        $return = array('status' => 0, 'html' => '');

        if (ifcmsnav($structure_id, $db_cmsnav, 'id')) {
            $return['status'] = 1;

            m()->set($db_cmsnav)->set('type_select', mdl_field_html_select($field_id));

            if (dbQuery('field')->id($field_id)->first($db_field)) {
                m()->set($db_field);
            }

            $html = m()->output('app/view/form.php');

            $return['html'] = $html;
        }

        return $return;
    }

    public function __async_save($structure_id, $field_id = null)
    {
        if (!dbQuery('\samson\cms\field\CMSField')->id($field_id)->first($field)) {
            $field = new CMSField(false);
        }

        // Update field data
        $field->update($structure_id);

        return array('status' => 1);
    }

    public function __async_delete($structure_id, $field_id)
    {
        /** @var \samson\cms\CMSNavField $relation */
        if (dbQuery('\samson\cms\CMSNavField')->FieldID($field_id)->StructureID($structure_id)->first($relation)) {
            $relation->delete();
        }
        return array('status' => 1);
    }

    public function __async_renderfields($structure_id)
    {
        $db_structure = dbQuery('\samson\cms\web\CMSNav')->id($structure_id)->first();
        $fields = dbQuery('\samson\cms\field\CMSField')
            ->join('\samson\cms\CMSNavField')
            ->cond('StructureID', $structure_id)
            ->exec();
        $items = '';

        if (sizeof($fields)) {
            foreach ($fields as $field) {
                $items .= m()->view('form/field_item')->field($field)->structure($db_structure)->output();
            }
        } else {
            $items = m()->view('form/empty_field')->output();
        }

        return array('status' => 1, 'fields' => $items);
    }
}

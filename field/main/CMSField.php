<?php
/**
 * Created by Pavlo Onysko <onysko@samsonos.com>
 * on 28.07.14 at 18:39
 */
 namespace samson\cms\web\field;

/**
 *
 * @author Pavlo Onysko <onysko@samsonos.com>
 * @copyright 2014 SamsonOS
 * @version 
 */
class CMSField extends \samson\cms\CMSField
{
    public static function renderTable($nav = 0, $page = 0, & $pager = null)
    {
        // Set new pager
        $pager = new \samson\pager\Pager($page, 5, 'field/updatetable/'.$nav.'/');

        // Create SamsonCMS fields table
        $table = new Table($pager, $nav);

        // Render view
        return m()
            ->view('index')
            ->title('Дополнительные поля')
            ->set('table', $table->render(null, $nav))
            ->set($pager)
            ->output();
    }

    /**
     * @param int $type
     *
     * @return string
     */
    public static function createSelect($type = 0)
    {
        // Create html view
        $html = '';

        // Define all types of data
        $typeData = array(
            'Текст' => 0,
            'Ресурс' => 1,
            'Дата' => 3,
            'Select' => 4,
            'Таблицы' => 5,
            'Материал' => 6,
            'Число' => 7,
            'WYSIWYG' => 8
        );

        foreach ($typeData as $key => $value) {
            // Check selected status
            $selected = ($type == $value) ? 'selected' : '';

            // Create options of select
            $html .= '<option value="' . $value . '" ' . $selected . '>' . $key . '</option>';
        }

        // Return view
        return '<select name="Type" id="Type">' . $html . '</select>';
    }

    /**
     * Updating field and creating relation with structure
     * @param int $structureID
     */
    public function update($structureID = 0)
    {
        // Fill the fields from $_POST array
        foreach ($_POST as $key => $val) {
            $this[$key]=$val;
        }

        $this->save();
        /** @var \samson\cms\web\CMSNav $cmsnav */

        // If isset current structure
        if (dbQuery('\samson\cms\web\CMSNav')->id($structureID)->first($cmsnav)) {

            // If structure has not relation with current field
            if (!dbQuery('structurefield')->StructureID($cmsnav->id)->FieldID($this->id)->first()) {

                // Create new relation between structure and field
                $strField = new \samson\activerecord\structurefield(false);
                $strField->FieldID = $this->id;
                $strField->StructureID = $cmsnav->id;
                $strField->Active = 1;

                // Save relation
                $strField->save();
            }
        }
    }
}

<?php
/**
 * Created by Pavlo Onysko <onysko@samsonos.com>
 * on 28.07.14 at 18:39
 */
 namespace samson\cms\field;

/**
 *
 * @author Pavlo Onysko <onysko@samsonos.com>
 * @copyright 2014 SamsonOS
 * @version 
 */
class CMSField extends \samson\cms\CMSField
{
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

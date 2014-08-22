<?php
namespace samson\cms\web\field;

use samson\cms\input\Field;
use samson\activerecord\dbQuery;
use samson\pager\Pager;

/**
 * Class for rendering SamsonCMS Field table
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class Table extends \samson\cms\table\Table
{
    /**
     * @param Pager $pager
     * @param int   $navID
     */
    public function __construct(Pager & $pager = null, $navID = 0)
    {
        // Prepare db query
        $this->query = dbQuery('samson\cms\cmsfield')
            ->join('samson\cms\cmsnavfield')
            ->join('samson\cms\cmsnav')
            ->order_by('FieldID', 'ASC');

        if (dbQuery('structure')->id($navID)->first($structure)) {
            $this->query = $this->query->cond('StructureID', $structure->id);
        }
        // Constructor tree
        parent::__construct($this->query, $pager);
    }

    /**
     * @param array $db_rows
     * @param int   $navID
     *
     * @return string
     */
    public function render(array $db_rows = null, $navID = 0)
    {
        // Rows HTML
        $rows = '';

        // if no rows data is passed - perform db request
        if (!isset($db_rows)) {
            $db_rows = $this->query->exec();
        }

        // If we have table rows data
        if (is_array($db_rows)) {
            // Save quantity of rendering rows
            $this->last_render_count = sizeof($db_rows);

            // Iterate db data and perform rendering
            foreach ($db_rows as & $db_row) {
                $rows .= $this->row($db_row, $this->pager, $navID);
            }
        } else {
            // No data found after query, external render specified
            $rows .= $this->emptyrow($this->query, $this->pager);
        }

        // Render table view
        return m()
            ->view($this->table_tmpl)
            ->set($this->pager)
            ->rows($rows)
            ->output();
    }

    /**
     * @param Object $db_row
     * @param Pager  $pager
     * @param int    $navID
     *
     * @return bool|string
     */
    public function row(& $db_row, Pager & $pager = null, $navID = 0)
    {
        if (!empty($db_row->onetoone['_structure'])) {
            m()->set($db_row->onetoone['_structure']);
        }
        // Render field row
        return m()
            ->set($db_row, 'field')
            ->set($pager)
            ->navID($navID)
        ->output('table/row/index');
    }
}

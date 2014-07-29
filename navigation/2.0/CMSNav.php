<?php
/**
 * Created by PhpStorm.
 * User: p.onysko
 * Date: 03.03.14
 * Time: 13:46
 */
namespace samson\cms\web;


use samson\activerecord\dbRelation;

class CMSNav extends \samson\cms\CMSNav
{
    /**
     * Filling the fields and creating relation of structure
     */
    public function fillFields()
    {
        // Fill the fields from $_POST array
        foreach ($_POST as $key => $val) {
            $this[$key]=$val;
        }

        // Save object
        $this->save();

        // Create new relation
        $strRelation = new \samson\activerecord\structure_relation(false);
        $strRelation->parent_id = $_POST['ParentID'];
        $strRelation->child_id = $this->id;

        // Save relation
        $strRelation->save();
    }

    /**
     * Updating structure
     */
    public function update()
    {
        /** @var array $relations array of structure_relation objects */
        $relations = null;

        // If CMSNav has old relations then delete it
        if (dbQuery('\samson\activerecord\structure_relation')->child_id($this->id)->exec($relations)) {
            /** @var \samson\activerecord\structure_relation $relation */
            foreach ($relations as $relation) {
                $relation->delete();
            }
        }

        // Update new fields
        $this->fillFields();
    }

    public static function fullTree(CMSNav & $parent = null)
    {
        $html = '';
        $newNavs = array();

        if (!isset($parent)) {
            $parent = new CMSNav(false);
            $parent->Name = 'Корень навигации';
            $parent->Url = 'NAVIGATION_BASE';
            $parent->StructureID = 0;
            $parent->base = 1;
            $db_navs = null;
            if (dbQuery('samson\cms\web\cmsnav')
                ->Active(1)
                ->join('parents_relations')
                ->cond('parents_relations.parent_id', '', dbRelation::ISNULL)
                ->order_by('PriorityNumber', 'ASC')
                ->exec($db_navs)) {
                foreach ($db_navs as $db_nav) {
                    $parent->children['id_'.$db_nav->id] = $db_nav;
                }
            }
        }
        /*$cmsnavs = dbQuery('\samson\cms\web\CMSNav')->cond('Active',1)
            ->order_by('PriorityNumber','asc')->exec();

        foreach ($cmsnavs as $cmsnav) {
            $newNavs[$cmsnav->Url] = $cmsnav;
        }
        self::build($parent, $newNavs);*/
        //elapsed('startHtmlTree');
        $htmlTree = $parent->htmlTree($parent, $html, 'tree.element.tmpl.php');
        //elapsed('endHtmlTree');
        return $htmlTree;
    }

    public function htmlTree(CMSNav & $parent = NULL, & $html = '', $view = NULL, $level = 0 )
    {
        if (!isset($parent)) {
           $parent = & $this;
        }
        if ($parent->base){
            $children = $parent->children();
        } else {
            $children = $parent->baseChildren();
        }
        //trace($parent->Name);
        //$children = $parent->children();
        //trace(sizeof($children).' - '.$level);
        if (sizeof($children)) {
            $html .= '<ul>';
            foreach ($children as $id => $child) {
                if (isset($view)) {
                    $html .= '<li>'
                        .m()->view($view)->parentid($parent->id)->db_structure($child)->output().'';
                } else {
                    $html .= '<li>'.$child->Name.'</li>';
                }
                //if ($level < 5)
                    $parent->htmlTree($child, $html, $view, $level++);
                $html .= '</li>';
            }
            $html .='</ul>';
        }

        return $html;
    }
}

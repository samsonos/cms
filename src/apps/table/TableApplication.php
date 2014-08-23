<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 23.08.14 at 10:56
 */
 namespace samson\cms\web\table;
 use samson\cms\App;
 use samson\cms\table\Table;

 /**
 * Generic table application for SamsonCMS
 * @author Vitaly Egorov <egorov@samsonos.com>
 * @copyright 2014 SamsonOS
 */
class TableApplication extends App
{
    /** @var string Database entity name */
    public $entity;

    /** @var \samson\activerecord\dbQuery Draw entity database query */
    public $drawQuery;

    /** @var \samson\activerecord\dbQuery Delete entity database query */
    public $deleteQuery;

    /** @var \samson\activerecord\dbQuery Save entity database query */
    public $saveQuery;

    /** @var  Object Pointer to entity render */
    public $entityRenderer;

    /** @var string Path to main application view */
    public $mainView = 'main/index';

    /** @see \samson\core\ExternalModule:init() */
    public function init(array $params = array())
    {
        // If no query is passed use generic query
        $this->drawQuery = isset($this->drawQuery) ? $this->drawQuery : dbQuery($this->entity)->cond('Active', 1);
        $this->deleteQuery = isset($this->deleteQuery) ? $this->deleteQuery : dbQuery($this->entity)->cond('Active', 1);
        $this->saveQuery = isset($this->saveQuery) ? $this->saveQuery : dbQuery($this->entity)->cond('Active', 1);

        // If no external render object is passed - use generic table render
        $this->entityRenderer = isset($this->entityRenderer) ? new $this->entityRenderer($this->drawQuery) : new Table($this->drawQuery);
    }

    /** Generic universal controller for rendering main application page */
    public function __handler()
    {
        $this->view($this->mainView)        // Set current view scope
            ->title(t($this->name, true))   // Set title
            ->set($this->__ajax_draw())     // Render view
        ;
    }

    /**
     * Generic asynchronous entities rendering controller action
     *
     * @return array Asynchronous response array
     */
    public function __ajax_draw()
    {
        // Return asynchronous response array
        return array(
            'status' => 1,
            'html'  => $this->entityRenderer->render()
        );
    }

    /**
     * Generic asynchronous entity deleting controller action,
     * no database record is actually deleted, only special field [Active] is
     * set to 0, to enable data restoring.
     *
     * @param string $entityID Entity identifier to delete
     *
     * @return array Asynchronous response array
     */
    public function __ajax_delete($entityID)
    {
        $result = array('status' => 0);

        /** @var \samson\activerecord\dbRecord $dbObject */
        $dbObject = null;
        // Find entity
        if ($this->deleteQuery->id($entityID)->first($dbObject)) {
            // Set special field to 0
            $dbObject['Active'] = 0;
            $dbObject->save();

            $result['status'] = 1;
        }

        return $result;
    }

    /**
     * Generic asynchronous entity saving controller action.
     * Function automatically analyzez posted data from $_POST array
     * and sets only equivalent fields into entity.
     *
     * @param string|null $entityID Current saving entity identifier, if null new entity will be created
     *
     * @return array Asynchronous response array
     */
    public function __ajax_save($entityID = null)
    {
        $result = array('status' => 0);

        // If form has been sent
        if (isset($_POST)) {

            // Create or find user depending on UserID passed
            /** @var \samson\activerecord\user $dbObject */
            $dbObject = null;
            if (!isset($entityID) || !$this->saveQuery->id($entityID)->first($dbObject)) {
                $dbObject = new $this->entity(false);
            }

            // Iterate all submitted fields
            foreach ($_POST as $key => $value) {
                // If db entity has this field
                if (isset($dbObject[$key])) {
                    // Store it
                    $dbObject[$key] = $value;
                }
            }

            // Save object to db
            $dbObject->save();

            // Set client response data
            $result['status'] = 1;
            $result['entity'] = $dbObject;
        }

        return $result;
    }
}
 
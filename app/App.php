<?php 
namespace samson\cms;

use samson\core\CompressableExternalModule;

/**
 * SamsonCMS external compressable application for integrating
 * @author Vitaly Iegorov <egorov@samsonos.com>
 */
class App extends CompressableExternalModule
{
    /**
     * Collection of loaded SamsonCMS applications
     * @var App[]
     */
    protected static $loaded = array();

    /**
     * Get all loaded SamsonCMS applications
     * @return App[] Collection of loaded applications
     */
    public static function loaded()
    {
        return self::$loaded;
    }

    /**
     * Find SamsonCMS application by identifier
     * @param string $id SamsonCMS application identifier
     * @param mixed $app Variable to return found SamsonCMS application
     * @return boolean True if SamsonCMS application has been found
     */
    public static function find($id, & $app = null)
    {
        // Clear var as someone can pass anything in it
        $app = null;

        // If "normal" identifier is passed
        if (isset($id{0})) {
            // Try to find loaded system module
            $app = m($id);
        }

        return $app != null;
    }

    /** Application name */
    public $name;

    /** Flag for showing CMSApplication in main menu */
    public $hide = false;

    /** Constructor */
    public function __construct($path = null, $vid = null, $resources = null)
    {
        // Save CMSApplication instance
        if (get_class($this) !== __CLASS__) {
            self::$loaded[ $this->id ] = & $this;
        }

        parent::__construct($path, $vid, $resources);
    }

    /** Generic handler for rendering SamsonCMS application "Main page" */
    public function main()
    {
        return false;
    }

    /** Generic handler for rendering SamsonCMS application "Sub-menu" */
    public function submenu()
    {
        return false;
    }

    /** Generic handler for rendering SamsonCMS application "Help" */
    public function help($category = null)
    {
        if ($this->findView('help/index')) {
            return $this->view('help/index')->output();
        } else {
            return false;
        }
    }

    /** Deserialization handler */
    public function __wakeup()
    {
        parent::__wakeup();

        // Add instance to static collection
        self::$loaded[ $this->id ] = & $this;
    }
}

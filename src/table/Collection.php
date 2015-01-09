<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 26.12.2014
 * Time: 16:10
 */
namespace samsonos\cms\web;

use samson\cms\GenericCollection;

/**
 * Generic SamsonCMS entities collection
 * @package samsonos\cms\web
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class Collection extends GenericCollection
{
    /** @var array Collection of structure to get products */
    protected $structures = array(0);

    /** @var int Amount of tours at one page */
    protected $pageSize = 15;

    /** @var array Collection of inner db handlers */
    protected $innerDBHandlers = array();

    /** @var array Collection of outer db handlers */
    protected $outerDBHandlers = array();

    /** @var string Query resulting entity name */
    protected $entityName = 'samson\cms\CMSMaterial';

    /** @var string Empty view file */
    protected $emptyView = '';

    /** @var  \samson\pager\Pager Pagination */
    protected $pager;

    /**
     * Render products collection block
     * @param string $prefix Prefix for view variables
     * @param array $restricted Collection of ignored keys
     * @return array Collection key => value
     */
    public function toView($prefix = null, array $restricted = array())
    {
        // Render pager and collection
        return array(
            $prefix.'html' => $this->render(),
            $prefix.'pager' => $this->pager->toHTML()
        );
    }

    /**
     * Pager db request handler
     * @param \samson\activerecord\dbQuery $query
     */
    public function dbPagerHandler(&$query)
    {
        // Create count request to count pagination
        $countQuery = clone $query;
        $this->pager->update($countQuery->count());

        // Set current page query limits
        $query->limit($this->pager->start, $this->pager->end);
    }

    /** Fill collection with data */
    public function fill()
    {
        // Perform CMS request to get tours
        if (CMS::getMaterialsByStructures(
            $this->structures,
            $this->collection,
            $this->entityName,
            $this->outerDBHandlers,
            array(),
            $this->innerDBHandlers
        )) {
            // Handle success result
        }

        return $this->collection;
    }

    /**
     * Constructor
     * @param \samson\core\IViewable $renderer View render object
     * @param mixed $structure Collection of structures or single structure
     * @param int $page Current page number
     */
    public function __construct($renderer, $structure = array(), $page = 1)
    {
        // Create pagination
        $this->pager = new Pager($page, $this->pageSize);

        // Convert structure to array and merge
        $this->structures = array_merge($this->structures, !is_array($structure) ? array($structure) : $structure);

        // Add pager handler to outer db handlers collection
        $this->outerDBHandlers[] = array($this, 'dbPagerHandler');

        // Fill collection
        $this->collection = $this->fill();

        // Call parents
        parent::__construct($renderer);
    }
}

<?php
namespace samson\cms\web\material;

use samson\activerecord\Argument;
use samson\activerecord\Condition;
use samson\activerecord\dbRelation;
use samson\activerecord\dbConditionGroup;
use samson\activerecord\dbConditionArgument;
use samson\cms\Navigation;
use samson\pager\pager;
use samson\activerecord\dbMySQLConnector;

/**
 * Class for dislaying and interactiong with SamsonCMS materials table
 * @author Egorov Vitaly <egorov@samsonos.com>
 */
class Table extends \samson\cms\table\Table
{	
	/** Table rows count */
	const ROWS_COUNT = 15;
	
	/** Parent materials CMSNav */
	protected $nav;
	
	/** Current search keywords */
	protected $search;
	
	/** Array of drafts for current materials */	
	protected $drafts = array();
	
	/** Array of drafts with out materials */
	protected $single_drafts = array();
	
	/** Search material fields */
	public $search_fields = array( 'Name', 'Url'  );	
	
	/** Default table template file */
	public $table_tmpl = 'table/index';
	
	/** Default table row template */
	public $row_tmpl = 'table/row/index';
	
	/** Default table notfound row template */
	public $notfound_tmpl = 'table/row/notfound';

	/** Default table empty row template */
	public $empty_tmpl = 'table/row/empty';
	
	/**
	 * Prepare DB query search condition by keywords
	 * @param string $keywords Keywords condition to add to query 
	 */
	private function _prepareSearchCondition( $keywords )
	{
		// If keywords has chars
		if ( isset( $keywords{0} ) )
		{			
			// Create condition group
			$scg = new Condition('or');

			// Iterate base material and nav fields to generate search conditions
			foreach ( $this->search_fields as $item ) {
				// If condition group is passed - add it to search condition				
				if( is_a($item, \samson\core\Autoloader::className('Condition', 'samson\activerecord')) ) $scg->arguments[] = $item;
				// Create condition argument
				else $scg->arguments[] = new Argument( $item, '%'.$keywords.'%', dbRelation::LIKE );
			}
		
			// Add query condition
			$this->query->own_condition->arguments[] = $scg;
		}
	}
	
	/**
	 * Get array of material drafts for specified materials
	 * @param array $db_materials Collection of parent materials
	 * @return array Collection of drafts
	 */
	private function & _getDrafts( array & $db_materials )
	{
		$result = array();
		
		// Collect found materials ids
		$ids = array();
		foreach ( $db_materials as $db_material ) {
            $ids[] = $db_material->id;
        }
			
		// Get drafts for found materials
        $drafts = array();
		if (cmsquery()->Draft($ids)/*->UserID(auth()->user->id)*/->exec($drafts))	{
			// Save drafts by their parent material
			foreach ( $drafts as $draft ) {
                $result[ $draft->Draft ] = $draft;
            }
		}		
		
		return $result;
	}
	
	/**
	 * Constructor 
	 * @param CMSNav $nav 		Parent CMSNav to filter materials
	 * @param string $search	Keywords to search in materials
	 * @param string $page		Current table page number
	 */
	public function __construct( Navigation & $nav = null, $search = null, $page = null )
	{			
		// Save parent cmsnav
		$this->nav = & $nav;
		
		// Save search keywords
		$this->search = $search;
		
		// Generate pager url prefix
		$prefix = 'material/table/'.(isset($nav) ? $nav->id : '0').'/'.(isset($search{0}) ? $search : 'no-search').'/';
		
		// Create pager
		$this->pager = new \samson\pager\Pager( $page, self::ROWS_COUNT, $prefix );		

		// Create DB query object
		$this->query = dbQuery('samson\cms\cmsmaterial')
			->Draft(0)
			->Active(1)
			->own_order_by('Modyfied', 'DESC')
			->join('user')
			->join('structurematerial')
			->join('samson\cms\Navigation')
		;				

        /*if (isset($search)) {
            $condition = new Condition('OR');
            $condition->add('Name', '%'.$search.'%', dbRelation::LIKE);
            $condition->add('Url', '%'.$search.'%', dbRelation::LIKE);
            //$condition->add('MaterialID', intval($search));
            $this->query = $this->query->cond($condition);
        }*/

		// Perform query by cmsnavmaterial and get material ids
		if( isset($nav) && dbQuery('samson\cms\cmsnavmaterial')->StructureID( $nav->id )->Active( 1 )->fields('MaterialID', $ids))
		{		
			// Set corresponding material ids related to specified cmsnav
			$this->query->id($ids);				
		}			
			
		// Call parent constructor
		parent::__construct( $this->query, $this->pager );
	}
	
	/** @see \samson\cms\table\Table::render() */
	public function render( array $db_rows = null )
	{		
		// If no rows is passed use generic rows
		if( !isset( $db_rows ) ) 
		{			
			// If search filter is set - add search condition to query
			if( isset($this->search) ) $this->_prepareSearchCondition( $this->search );
			
			//db()->debug();
			// Get original materials
			if( $this->query->exec( $db_materials ) )
			{						
				// Get drafts for found materials
				$this->drafts = $this->_getDrafts( $db_materials );	

				// Render drafts without original materials
				/*if(cmsquery()
						->join('samson\cms\cmsnav')
						->where(dbMySQLConnector::$prefix.'material.Draft = '.dbMySQLConnector::$prefix.'material.MaterialID')
						->UserID( auth()->user->id )
						->exec($this->single_drafts)						
				)
				{
					// Add single drafts to the begining of the table
					$db_materials = array_merge( $this->single_drafts, $db_materials ); 
				}*/
				
				// Generic rendering routine
				return parent::render( $db_materials );
			}	
			// Query failed	
			else 
			{
				// Render empty\notfound row content
				$row = '';
				if(!isset($this->search{0})) $row = $this->emptyrow( $this->query, $this->pager );
				else $row = m()->output($this->notfound_tmpl);
					 
				// Manually render table
				return m()
					->view( $this->table_tmpl )
					->set( $this->pager )
					->rows( $row )
				->output();
			}
			 
			//db()->debug(false);		
		}
		
		// Perform table rendering
		return parent::render( $db_rows );
	}
	
	/** @see \samson\cms\table\Table::row() */
	public function row( & $db_material, Pager & $pager = null )
	{			
		// Set table row view context
		m()->view(  $this->row_tmpl );
		
		// If there is cmsnav for material pass them
		if( isset( $db_material->onetomany['_structure'] )) m()->navs( $db_material->onetomany['_structure'] ); 
		
		// If there is a draft for this material, pass draft to view
		if( isset( $drafts[ $db_material->id ] )) m()->draft( $this->drafts[ $db_material->id ] ); 	
		
		// Render row template
		return m()						
			->cmsmaterial( $db_material )
			->user( isset($db_material->onetoone['_user']) ? $db_material->onetoone['_user'] : '' )			
			->pager( $this->pager )
			->nav_id( isset($this->nav) ? $this->nav->id : '0' )	
			->search(urlencode($this->search))		
		->output(); 	
	}
}
<?php
namespace samson\cms\user;
use samson\pager\Pager;

class Table extends \samson\cms\Table\Table
{
	public function row( & $db_row, Pager & $pager = null )
	{
		return m()->user( $db_row )->set( $pager )->output( $this->row_tmpl );
	}
} 
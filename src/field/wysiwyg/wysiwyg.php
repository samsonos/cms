<?php
namespace samson\cms\input;

/**
 * SamsonCMS WYSIWYG input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 */
class Wysiwyg extends Field 
{		
	/** Special CSS classname for nested field objects to bind JS and CSS */
	protected $cssclass = '__wysiwyg';

    /** Module dependencies */
    protected $requirements = array(
        //'ckeditor'
    );
}
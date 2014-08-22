<?php
namespace samson\cms\input;

/**
 * SamsonCMS WYSIWYG input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 */
class Wysiwyg extends Field 
{
    /** @var string Module identifier */
    public $id = 'samson_cms_input_wysiwyg';

	/** Special CSS classname for nested field objects to bind JS and CSS */
	protected $cssclass = '__wysiwyg';
}
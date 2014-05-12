<?php
namespace samson\cms\input;

/**
 * Generic SamsonCMS input field
 * @author Vitaly Iegorov<egorov@samsonos.com>
 *
 */
class Date extends Field 
{
    public function numericValue($input)
    {
        // Convert to timestamp
        return strtotime($input);
    }
}
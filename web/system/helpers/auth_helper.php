<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @return	boolean
 */
if ( ! function_exists('check_email'))
{
	function check_email($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

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
if ( ! function_exists('check_password'))
{
	function check_password($password)
	{
		if (strlen($password) < 6)
		{
			return false;
		}

		if (preg_match('/([a-z]+[0-9]+)|([0-9]+[a-z]+)/i', $password))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

if (!(function_exists('check_resetcode')))
{
	function check_resetcode($code)
	{
		if (preg_match('/[a-f0-9]{32}/', $code))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Assets URL
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('assets_url'))
{
	function assets_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url(''). 'assets/';
	}
}

?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('current_full_url'))
{
	/**
	 * Current URL
	 *
	 * Returns the full URL (including segments) of the page where this
	 * function is placed
	 *
	 * @return	string
	 */
	function current_full_url()
	{
		$CI =& get_instance();

		$url = $CI->uri->uri_string();
		return $CI->input->server('QUERY_STRING') ? $url .'?'. $CI->input->server('QUERY_STRING') : $url;
	}
}

// ------------------------------------------------------------------------
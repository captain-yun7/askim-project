<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('db_where'))
{	
	/*
	 * database/database.php where 함수 개선 (=, AND는 생략가능)
	 *
	 * @param array $data (field, value, operator, type, group) operator (=, <, >, <>, !=, <=, >=, NOTIN, IN),type (OR, AND), group ( @array [0] = (, ) [1] = AND, OR)
	 *	@return array 
	 */
	function db_where($data, $escape = true) {
		$CI =& get_instance();
		$result = array();
		foreach($data as $key) {
			if(isset($key[4])) {
				if(isset($key[4][0]) && trim($key[4][0]) == "(") {
					if(isset($key[4][1]) && strtolower($key[4][1]) == "or") {
						$CI->db->or_group_start();
					} else {
						$CI->db->group_start();
					}
				} 
			}
			if(isset($key[2]) && (bool)preg_match('/(\bNOTIN\b)/i', trim($key[2]))) {
				if(isset($key[3]) ) { //
					if(strtolower($key[3]) == "or") {
						$result[] = $CI->db->or_where_not_in($key[0], $key[1], $escape);
					} else {
						$result[] = $CI->db->where_not_in($key[0], $key[1], $escape);
					}
				} else {
					$result[] = $CI->db->where_not_in($key[0], $key[1], $escape);
				}
			} else if(isset($key[2]) && (bool)preg_match('/(\bIN\b)/i', trim($key[2]))) {
				if(isset($key[3]) ) { //
					if(strtolower($key[3]) == "or") {
						$result[] = $CI->db->or_where_in($key[0], $key[1], $escape);
					} else {
						$result[] = $CI->db->where_in($key[0], $key[1], $escape);
					}
				} else {
					$result[] = $CI->db->where_in($key[0], $key[1], $escape);
				}
			} else {
				$key[0] = $key[0] .(isset($key[2]) && (bool)preg_match('/(<|>|!|=)/i', trim($key[2])) ? " ".$key[2] : "");

				if(isset($key[3]) ) { 
					if(strtolower($key[3]) == "or") {
						$result[] = $CI->db->or_where($key[0], $key[1], $escape);
					} else {
						$result[] = $CI->db->where($key[0], $key[1], $escape);
					}
				} else {
					$result[] = $CI->db->where($key[0], $key[1], $escape);
				}
			}

			if(isset($key[4])) {
				if(isset($key[4][0]) && trim($key[4][0]) == ")") {
					$CI->db->group_end();
				} 
			}
		}
		return $result;
	}
}


if ( ! function_exists('db_like'))
{	
	/*
	 * database/database.php like 함수 개선
	 *
	 * @param array $data (field, value, side, type, group) operator (both, before, after),type (OR, AND) 3번째 both, group ( @array [0] = (, ) [1] = AND, OR) 4,5번째 AND 생략가능, 
	 *	@return array 
	 */
	function db_like($data, $escape = true) {
		$CI =& get_instance();
		$result = array();

		foreach($data as $key) {
			if(isset($key[4])) {
				if(isset($key[4][0]) && trim($key[4][0]) == "(") {
					if(isset($key[4][1]) && strtolower($key[4][1]) == "or") {
						$CI->db->or_group_start();
					} else {
						$CI->db->group_start();
					}
				} 
			}

			if(!(isset($key[2]) && in_array(strtolower($key[2]), array("both", "before", "after")))) {
				$key[2] = "both";

				if(isset($key[3])) { //
					if(strtolower($key[3]) == "or") {
						$result[] = $CI->db->or_like($key[0], $key[1], $key[2], $escape);
					} else {
						$result[] = $CI->db->like($key[0], $key[1], $key[2], $escape);
					}
				} else {
					$result[] = $CI->db->like($key[0], $key[1], $key[2], $escape);
				}
			} else if((bool)preg_match('/(\bNOTIN\b)/i', trim($key[2]))) {
				if(isset($key[3])) { //
					if(strtolower($key[3]) == "or") {
						$result[] = $CI->db->or_not_like($key[0], $key[1], $key[2], $escape);
					} else {
						$result[] = $CI->db->not_like($key[0], $key[1], $key[2], $escape);
					}
				} else {
					$result[] = $CI->db->not_like($key[0], $key[1], $key[2], $escape);
				}
			}

			if(isset($key[4])) {
				if(isset($key[4][0]) && trim($key[4][0]) == ")") {
					$CI->db->group_end();
				} 
			}
		}
		return $result;
	}
}

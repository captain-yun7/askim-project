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

/**
 * Inbet 기본 내장 Helpers
 *
 * @package		Inbet
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Inbet RnD Team
 */

// ------------------------------------------------------------------------

if ( ! function_exists('weblog'))
{
	/**
	 * 서버 로그
	 *
	 * @param	string	$msg	저장할 로그 메세지
	 * @param	int		$level	저장할 로그 타입
	 */
	function weblog($msg, $type = null)
	{
		$log_path = BASEPATH ."../data/log";
		if(!is_dir($log_path)){
			@mkdir($log_path, 0777);
		}

		if($type) {
			$log_path .= "/". $type;
		}

		if($type && !is_dir($log_path)){
			@mkdir($log_path, 0777);
		}
		$logfile = $log_path ."/".date('Ymd').".log";

		if(!$type) {
			$msg = "[".date('Y-m-d H:i:s')."]\n".$msg."\n";
		}

		file_put_contents($logfile, $msg, FILE_APPEND);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('curlJsonData'))
{
	/**
	 * curl api 통신용 함수
	 *
	 * @param  string 	$url		호출 url
	 * @param  array	$param		파라미터
	 * @param  string	$method		메소드
	 * @return array      			결과
	 */
	function curlJsonData($url, $param, $method='post'){
		$ch = curl_init();

		if($method==='get'){
			$param = http_build_query($param);
			$url .= '?'.$param;
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		//weblog('curl connection start',5);
		//weblog($url,5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);

		$headers = array(
			"Cache-Control: no-cache",
			"Pragma: no-cache"
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if($method==='post'){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		}

		$_data = curl_exec($ch);
		curl_close($ch);
		return json_decode($_data, true);
	}
}

// ------------------------------------------------------------------------
/**
	 * strip_tags 확장함수
	 *
	 */
if ( ! function_exists('strip_tags_content'))
{
	function strip_tags_content($text, $tags = '', $invert = FALSE) {

		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
		$tags = array_unique($tags[1]);

		if(is_array($tags) AND count($tags) > 0) {
			if($invert == FALSE) {
				return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
			}
			else {
			  return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
			}
		} elseif($invert == FALSE) {
			return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
		}
		return $text;
	}
}
// ------------------------------------------------------------------------

if ( ! function_exists('debug'))
{
	/**
	 * 디버그 함수
	 *
	 * @param	mixed	$data	디버그 할 변수
	 * @return	string			디버그 결과 태그
	 */
	function debug($data){
        //if(defined("_DEVELOP") || defined("_DEBUG")) {
            print "<xmp style=\"position:relative; z-index:99999; display:block;font:9pt 'Bitstream Vera Sans Mono, Courier New';background:#202020;color:#D2FFD2;padding:10px;margin:5px;\">";
            print_r($data);
            print "</xmp>";
        //}
	}
}

// ------------------------------------------------------------------------



// ------------------------------------------------------------------------

if ( ! function_exists('byteCheck'))
{
	/**
	 * 문자열 바이트 체크(한글포함)
	 *
	 * @param	string	$str		바이트 체크할 문자열
	 * @param	string	$encoding	문자열 인코딩 타입
	 * @return	int					문자열 바이트
	 */
	//한글포함 문자열 바이트수 체크 1 or 2
	function byteCheck($str,$encoding='UTF-8'){
		$byte = 0;
		$len = mb_strlen($str,$encoding);
		for($i=0; $i<$len; $i++){
			if(strlen(mb_substr($str,$i,1,$encoding)) >= 2) {
				$byte += 2;
			} else {
				$byte++;
			}
		}
		return $byte;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('msg'))
{
	/**
	 * Alert 메세지 출력 후 페이지 이동
	 *
	 * @param	string	$msg	출력할 메세지
	 * @param	mixed	$code	메세지 출력 후 동작(페이지 이동일 시 url)
	 * @param	string	$target	메세지 출력 후 페이지 이동 시 타겟
	 */
	function msg($msg, $code=null, $target='')
	{
		echo "<script>alert('". str_replace("'", "\'", $msg) ."')</script>";
		switch (getType($code)){
			case "null":
				return;
			case "integer":
				if ($code) echo "<script>history.go($code)</script>";
				exit;
			case "string":
				if ($code=="close") echo "<script>window.close()</script>";
				else if($code=='parentClose') {
					echo "<script>parent.window.close()</script>";
				}
                else if($code=='parentRefresh') {
                    echo "<script>parent.location.reload();</script>";
                }
				else go($code,$target);
				exit;
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('go'))
{
	/**
	 * 페이지 이동
	 *
	 * @param	string	$url	이동할 페이지 url
	 * @param	string	$target 페이지 이동 시 타겟
	 */
	function go($url,$target='')
	{
		if($url == "-1") {
			if ($target) $target .= ".";
			echo "<script>{$target}history.back(-1);</script>";
			exit;
		} else {
			if ($target) $target .= ".";
			echo "<script>{$target}location.replace('$url')</script>";
			exit;
		}
	}
}


if ( ! function_exists('table_data_match'))
{

	/*
	 * 테이블 컬럼명과 배열키가 매치된 것만 데이터 리턴
	 * @param	 string	$table	테이블명
	 * @param	 array	$data	데이터
	 * @return	 array	$arrData	데이터
	 */
	function table_data_match($table, $data) {
		$CI =& get_instance();

		$arrField = array_flip($CI->db->list_fields($table));
		$arrData = array_intersect_key($data, $arrField);

		return $arrData;
	}
}

if ( ! function_exists('get_list_member_grade'))
{
	/*
	 * 회원등급 가져오기
	 * @param	 array	$arr_where	where
	 * @param	 array	$arr_like	like
	 * @param	 array	$arr_orderby	order by
	 *
	 * @return	 array data
	 */
	function get_list_member_grade($arr_where = null, $arr_like = null, $arr_orderby = null) {
		$CI =& get_instance();

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}

		if(isset($arr_orderby)) {
			$CI->db->order_by($arr_orderby);
		} else {
			$CI->db->order_by("level", "desc");
		}

		$CI->db->from("da_member_grade AS mg");
		$CI->db->join("(SELECT level AS member_level, IFNULL(COUNT(level), 0) cnt FROM da_member GROUP BY level) AS m", "mg.level = m.member_level", "LEFT");

		return $CI->db->get()->result_array();
	}
}

if ( ! function_exists('get_view_member_grade'))
{
	/*
	 * 회원등급 가져오기
	 * @param	 array	$arr_where	where
	 * @param	 array	$arr_like	like
	 *
	 * @return	 array data
	 */
	function get_view_member_grade($arr_where = null, $arr_like = null) {
		$CI =& get_instance();

		if(isset($arr_where)) {
			db_where($arr_where);
		}

		if(isset($arr_like)) {
			db_like($arr_like);
		}
		$CI->db->from("da_member_grade AS mg");
		$CI->db->join("(SELECT level AS member_level, IFNULL(COUNT(level), 0) cnt FROM da_member GROUP BY level) AS m", "mg.level = m.member_level", "LEFT");

		$result = $CI->db->get();
		if($result->result_id->num_rows) {
			return $result->last_row("array");
		}
		return false;
	}
}


if ( ! function_exists('get_man_age'))
{
	/*
	 * 만 나이 가져오는 함수
	 * @param	 array	$data	생년월일 (1990-06-06)
	 *
	 * @return	 int age
	 */
	function get_man_age($data) {
		$now = date('Ymd');

		$birth_time   = strtotime($data);
		$birthday = date("Ymd", $birth_time);
		$age = floor(($now - $birthday) / 10000);

		return $age;
	}
}

if ( ! function_exists('get_active_menu'))
{
	/*
	 * 사용중인 메뉴 가져오기
	 * @param	 array $menu
	 *
	 * @return	 array $menu
	 */
	function get_active_menu($menu) {
		if(isset($menu)) {
			foreach($menu as $first_key=> $first) {
				if(isset($first["menu"])) {
					foreach($first["menu"] as $second_key => $second) {
						if(isset($second["menu"])) {
							foreach($second["menu"] as $thrid_key => $thrid) {
								if(isset($thrid["menu"])) {
									foreach($thrid["menu"] as $fourth_key => $fourth) {
										if(($fourth["use"] != "y")) {
											unset($menu[$first_key]["menu"][$second_key]["menu"][$thrid_key]["menu"][$fourth_key]);
										}
									}
								}
								if(($thrid["use"] != "y")) {
									unset($menu[$first_key]["menu"][$second_key]["menu"][$thrid_key]);
									continue;
								}
								if(!count($menu[$first_key]["menu"][$second_key]["menu"][$thrid_key]["menu"])) {
									unset($menu[$first_key]["menu"][$second_key]["menu"][$thrid_key]["menu"]);
								}
							}
						}
						if(($second["use"] != "y")) {
							unset($menu[$first_key]["menu"][$second_key]);
							continue;
						}
						if(!count($menu[$first_key]["menu"][$second_key]["menu"])) {
							unset($menu[$first_key]["menu"][$second_key]["menu"]);

						}
					}
				}
				if(($first["use"] != "y")) {
					unset($menu[$first_key]);
					continue;
				}
				if(!count($menu[$first_key]["menu"])) {
					unset($menu[$first_key]["menu"]);
				}
			}
		}

		return $menu;
	}
}


if ( ! function_exists('print_language'))
{
	/*
	 * 언어에 맞는 언어 리턴
	 * @param	 array $language_code
	 * @param [, mixed $args [, mixed $... ]] )
	 *
	 * @return	 array $menu
	 */

	function print_language($language_code) {

		$CI =& get_instance();

		$lang = $CI->lang->line($language_code);
		$args = func_get_args();
		if(count($args) > 1) {
			$args[0] = $lang;
			$lang = call_user_func_array('sprintf', $args);
		}
		return $lang;
	}
}

if ( ! function_exists('include__'))
{
	function include_($id, $file)
	{
		$CI =& get_instance();
		$CI->template_->define($id, $CI->_skin . DIRECTORY_SEPARATOR . $file);
		$CI->template_->print_($id);
	}
}

if ( ! function_exists('include__display_main'))
{
	function include_display_main($id, $display_theme_no)
	{
		$CI =& get_instance();
		$CI->load->model('Front_Goods_model');
		$data = $CI->Front_Goods_model->design_display_main($display_theme_no);
		$CI->template_->assign('display_main_data', $data);
		if($data['display_main']['skin_type']) {
			include_($id, '/layout/display/'. $data['display_main']['skin_type']);
		}
	}
}

if ( ! function_exists('ib_isset'))
{
	/*
	 * var 데이터 검증
	 * @param string $var   검증데이터
	 * @param string $value false 일경우 반환
	 *
	 * @return	 array $var
	 */
	function ib_isset(&$var, $value = null, $debug = false)
	{
		if (isset($var) === false) {
            $var = null;
        }
        if (($var === null || (is_string($var) && $var == '')) && $value !== null) {
            $var = $value;
        }
        if ($debug === true) {
            var_dump($var);
            var_dump($value);
        }
        return $var;
	}
}
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha {
	private $CI;
	private $_captcha_path;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper("captcha");
		$this->_captcha_path = _UPLOAD . DIRECTORY_SEPARATOR .'captcha';
		$check = _DIR;
		foreach(explode(DIRECTORY_SEPARATOR, $this->_captcha_path) as $path){
			$check .= DIRECTORY_SEPARATOR . $path;
			if(!is_dir($check)){
				@mkdir($check, 0777);
			}
		}
	}

	/**
	 * 캡차 생성
	 *
	 * @param	 string	$page 캡차 호출페이지
	 * @param	 array	$vals	 
	 */
	public function get_captcha($page) {
		$capstr = (string)rand(11111111,99999999);
		$vals = array(
			'word' => $capstr,
			'img_path' => _DIR . $this->_captcha_path . DIRECTORY_SEPARATOR,
			'img_url' => $this->_captcha_path,
			'font_path' => BASEPATH .'/fonts/texb.ttf',
			'expiration' => 7200,
			'word_length' => 8,
			'img_width'	=> '180',
			'img_height'	=> '40',
			'font_size' => 16,
			'img_id' => 'captcha',
		);

		$captcha = create_captcha($vals);
		$this->CI->session->set_userdata(array("captcha" => array($page => $captcha)));
			
		return $captcha;
	}

	/**
	 * 세션캡차 가져오기
	 *
	 * @param  string	$page 캡차 호출페이지 
	 * @param	 array	$vals	
	 */
	public function get_sess_captcha($page) {
		$sess_captcha = $this->CI->session->__get("captcha");
		if(isset($sess_captcha[$page])) {
			return $sess_captcha[$page];
		}
		return false;	
	}
}
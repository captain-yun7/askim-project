<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include BASEPATH ."libraries/Email.php";

class Emailsend extends CI_Email {
	private $CI;
	private $message;
	private $mailForm;
	private $mail_display;
	private $_cfg_site;
	private $mail_form;
	private $mail_dir;

	public function __construct() {
		$this->mail_dir = BASEPATH ."..". DIRECTORY_SEPARATOR ."data". DIRECTORY_SEPARATOR ."mail_form". DIRECTORY_SEPARATOR;

		$this->CI =& get_instance();
		$this->CI->config->load("cfg_site");
		$cfg_site = $this->CI->config->item("cfg_site");
		$this->_cfg_site = $cfg_site["kor"];

		$this->CI->config->load("cfg_mailForm");
		$this->mailForm = $this->CI->config->item("cfg_mailForm");

		$this->CI->config->load("email");
		$email = $this->CI->config->item("email");

		parent::__construct($email);
	}

	public function get_mail_form($mailFormKey = null) {
		$this->mail_display = $this->mailForm[$mailFormKey];

		if(!file_exists($this->mail_dir . $this->mail_display)) {
			throw new Exception("메일 전송폼이 존재하지 않습니다.");
		}

		ob_start();
		include $this->mail_dir . $this->mail_display;
		$this->mail_form = ob_get_contents();
		ob_end_clean();

		$this->_cfg_site = array_merge($this->_cfg_site, array("base_url()" => base_url()));

		foreach($this->_cfg_site as $key => $value) {
			$this->mail_form = str_replace("{\$".$key."}", $value, $this->mail_form);
		}
	}

	public function message_bind($data) {
		foreach($data as $key => $value) {
			$this->mail_form = str_replace("{\$".$key."}", $value, $this->mail_form);
		}

		$this->message($this->mail_form);
	}

	/*
	 * 메일셋팅
	 *
	 * @param array
	 *
	 * @return boolean
	 */
	public function mail_form($data){
		$this->set_mailtype("html");
		if(isset($data["from"])) {
			$this->from($data["from"]);
		} else {
			$this->from($this->_cfg_site["adminEmail"], $this->_cfg_site["nameKor"]);
		}

		if(isset($data["to"])) {
			$this->to($data["to"]);
		}

		if(isset($data["cc"])) {
			$this->cc($data["cc"]);
		}

		if(isset($data["bcc"])) {
			$this->bcc($data["bcc"]);
		}

		if(isset($data["subject"])) { //title
			$this->subject(addslashes($data["subject"]));
		}

		if(isset($data["message"])) { //body
			$this->message($data["message"]);
		}

		if(isset($data["attach"])) { //file
			if(is_array($data["attach"])) {
				foreach($data["attach"] as $file) {
					$attach_path = _DIR . _UPLOAD;
					$attach_file = $attach_path . $file["fname"];
					//attach( $ filename [ , $ disposition = '' [ , $ newname = NULL [ , $ mime = '' ] ] ] )
					$this->attach($attach_file, $file["disposition"]. $file["oname"], $file["mime"]);
				}
			}
		}
	}
}
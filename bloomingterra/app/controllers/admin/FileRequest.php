<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileRequest extends ADMIN_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following urls
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	 // 타입 배열
	private $_validate;

	public function __construct() {
		parent::__construct();
		$this->load->library("form_validation");
		$this->config->load("cfg_uploadValidate");
		$this->_validate = $this->config->item("cfg_uploadValidate");

        // 언어파일 로드 2020-06-19
		$CI =& get_instance();
		$CI->lang->load('common');
		$CI->lang->load('upload');
	}

	public function upload(){
		try {
			if(defined("_IS_AJAX")) {

                if ($_SERVER['CONTENT_LENGTH'] && !$_FILES && !$_POST && empty($this->input->get("code", true)) === false && empty($this->input->get("size", true)) === false) {
                    // 업로드 파일 용량이 너무 크면 FILES,POST 변수들이 빈 상태로 넘어옵니다, GET에 담겨있으나 POST누락인걸 트리거로 에러검출.
                    echo json_encode(array('error' => print_language("upload_size_over_php", $this->input->get("size", true))), JSON_UNESCAPED_UNICODE);
                    return;
                }

				$this->form_validation->set_rules("folder", print_language("filerequest_folder"), "trim|required|xss_clean");
				$this->form_validation->set_rules("type", print_language("filerequest_type"), "trim|required|xss_clean");
				$this->form_validation->set_rules("size", print_language("filerequest_size"), "trim|xss_clean|is_natural");
				$this->form_validation->set_rules("width", print_language("filerequest_width"), "trim|xss_clean|is_natural");
				$this->form_validation->set_rules("height", print_language("filerequest_height"), "trim|xss_clean|is_natural");

				if(!$this->form_validation->run()){
					$err = $this->form_validation->error_array();

					if(count($err) > 0) {
						$keys = array_keys($err);
						$error = $err[$keys[0]];
						echo json_encode(array('error' => $error));
						exit;
					}
				}

				$config = array();
				$file_name = date('YmdHis').'_'.mt_rand(1000 ,9999);
				$folder = $this->input->post("folder", true);
				$allowed_types = $this->input->post("type", true);
				$max_size = $this->input->post("size", true) * 1024;
				$max_width = $this->input->post("width", true);
				$max_height = $this->input->post("height", true);
				$resize_width = $this->input->post("resize_width",true);
				$resize_height = $this->input->post("resize_height",true);
				$encrypt_name = false;
				$remove_spaces = true;
				$file_ext_tolower = true;

				if(preg_match('/\.\./', $folder)) {
					echo json_encode(array('error' => print_language("upload_path_cannot_be_uploaded")));
					exit;
				}

				$upload_path = _DIR . $folder;
				$check = '';
				foreach(explode(DIRECTORY_SEPARATOR, $upload_path) as $path){
					$check .= "/".$path;
					if(!is_dir($check)){
						@mkdir($check, 0777);
					}
				}

				if(!in_array($allowed_types, array("favicon", "sitemap", "snsImage"))) {
					$config["file_name"] = $file_name;
				}
				$config["upload_path"] = $upload_path;
				$config["allowed_types"] = implode("|", $this->_validate["extension"][$allowed_types]);
				$config["max_size"] = $max_size;
				$config["max_width"] = $max_width;
				$config["max_height"] = $max_height;

				$config["encrypt_name"] = $encrypt_name;
				$config["remove_spaces"] = $remove_spaces;
				$config["file_ext_tolower"] = $file_ext_tolower;

				if(count($_FILES)) {
					$tmp = array_keys($_FILES);
					$file = $tmp[0];

					$this->load->library("upload", $config);

					if($config['max_size']) {
						if( ($_FILES['file']['size'] / 1024) > $config['max_size']) {
							echo json_encode(array('error' => print_language("upload_size_over_php", $this->input->post("size", true))), JSON_UNESCAPED_UNICODE);
							return;
						}
					}

					if(!$this->upload->do_upload($file)) {
						$result = array('error' => $this->upload->display_errors("", ""));
					} else {

						$result = array('data' => $this->upload->data());
						$result["data"]["folder"] = $folder;

						//이미지 리사이즈 Start
						if(!empty($resize_width) || !empty($resize_height)){
							$resize_config["image_library"] = "gd2";
							$resize_config["source_image"] = $result["data"]["full_path"];

							//width, height 둘 다 존재 시 비율 무시
							if(!empty($resize_width) && !empty($resize_height)){
								$resize_config['maintain_ratio'] = FALSE;
							}

							$resize_config['width']	= $resize_width;
							$resize_config['height']= $resize_height;

							$this->load->library("image_lib", $resize_config);
							if(!$this->image_lib->resize()){
								$error = $this->image_lib->display_errors();
								echo $error;
							}
						}
						//이미지 리사이즈 End
						unset($result["data"]["full_path"]);
						unset($result["data"]["file_path"]);

					}
					echo json_encode($result, JSON_UNESCAPED_UNICODE);
				} else {
					echo json_encode(array('error' => print_language("no_files_selected")), JSON_UNESCAPED_UNICODE);
				}
			} else {
				throw new Exception(print_language("inaccessible_pages"), -1);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), 1);
		}
	}

	public function remove(){
		try {

			if(defined("_IS_AJAX")) {
				$this->form_validation->set_rules("folder", print_language("filerequest_folder"), "trim|required|xss_clean");
				$this->form_validation->set_rules("fname", print_language("파일명은 필수값입니다."), "trim|required|xss_clean");
				if(!$this->form_validation->run()){
					$err = $this->form_validation->error_array();

					if(count($err) > 0) {
						$keys = array_keys($err);
						$error = $err[$keys[0]];
						echo json_encode(array('error' => $error));
						exit;
					}
				}
				$folder = $this->input->post("folder", true);
				$file_name = $this->input->post("fname", true);

				if(preg_match('/\.\./', $folder)) {
					echo json_encode(array('error' => print_language("upload_path_cannot_be_uploaded")));
					exit;
				}

				$upload_path = _DIR . $folder;

				$file_path = $upload_path . "/" . $file_name;
				if(!file_exists($file_path)){
					echo json_encode(array("error" => "해당 업로드 파일이 존재하지 않습니다.(".$file_path.")"));
					exit;
				}
				chmod($file_path, 0777);
				unlink($file_path);
				echo json_encode("success: ".$file_path);
				exit;
			}
		}catch(Exception $e) {
			echo json_encode($e->getMessage());
			exit;
		}
	}

	public function download() {
		try {
			$file = $this->input->get("file", true);
			$save_name = $this->input->get("save", true);

			$download_path = _DIR . _UPLOAD;
			$download_file = $download_path . $file;
			$download_info = pathinfo($download_file);

			if(!isset($file)) {
				throw new Exception("다운로드할 파일정보가 없습니다.");
			}

			if(!preg_match('/\/upload\//i', @realpath($download_info['dirname'])) || preg_match('/(\/data\/|\/\_compile\/|\/install\/|\/latest\/|\/lib\/|\/system\/)/i', @realpath($download_info['dirname'])) || preg_match('/\.\.\//i', $file)) {
				throw new Exception('다운로드 할수 없는 경로의 파일입니다.');
			}

			if(!is_dir($download_info['dirname'])) {
				throw new Exception('다운로드 경로가 존재하지 않습니다.');
			}

			if(!file_exists($download_file)) {
				throw new Exception("다운로드할 파일이 없습니다.");
			}

			$is_validate = false;
			foreach($this->_validate["extension"] as $value) {
				if(in_array($download_info["extension"], $value)) {
					$is_validate = true;
				}
			}

			if(!$is_validate) {
				throw new Exception("다운로드할 수 없는 파일입니다.");
			}

			if(!$save_name) {
				$save_name = $download_info["basename"];
			}

			$this->load->helper("download");
			force_download($save_name, file_get_contents($download_file));
		} catch(Exception $e) {
			msg($e->getMessage());
		}
	}
}

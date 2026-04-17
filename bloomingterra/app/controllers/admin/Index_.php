<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_ extends ADMIN_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
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
	public function index() {
		try{
			$this->set_view("admin/index");
		} catch(Exception $e) {
			msg($e);
		}
	}

	public function indexAdmin() {
		$this->set_view('admin/view/indexAdmin');
	}

	public function login() {
		try{
			$this->load->library("form_validation");
			$this->form_validation->set_rules("userid", "아이디", "trim|required|xss_clean");
			$this->form_validation->set_rules("password", "비밀번호", "trim|required|xss_clean");
			$this->form_validation->set_rules("return_url", "", "trim|xss_clean");

			$this->load->model("Admin_Member_model");

            // 슈퍼맨 설정 로드
		    $this->config->load("cfg_adm_superman");
		    $superadmin = $this->config->item("admin");

			if ($this->form_validation->run() == true) {
				if(ib_isset($superadmin) && $superadmin[$this->input->post('userid', true)] == $this->input->post("password", true)) {
				//if($this->input->post('userid', true) == "superman" && $this->input->post("password", true)) {
					$this->session->set_userdata(
						array(
							"admin_member" => array(
								"userid" => "superman",
								"name" => "관리자",
								"group" => "",
								"level" => 99,
								"super" => true
							)
						)
					);
				} else {
					$data = array(
						"userid" => $this->input->post("userid", true),
						"password" => $this->input->post("password", true),
						"encrypt" => $this->input->post("encrypt", true),
					);
					$get_data = $this->Admin_Member_model->login_chk($data);
					$msg = "";

					if(!$get_data) {
						throw new Exception("존재하지 않는 아이디입니다.");
					} else if($get_data["yn_password"] != "y") {
						throw new Exception("비밀번호가 일치 않습니다.");
					} else if($get_data["yn_status"] != "y"){
						throw new Exception("탈퇴한 회원아이디입니다.");
					} else if(!in_array($get_data["level"], array_keys($this->_adm_auth))) {
						throw new Exception("권한이 없습니다.");
					}

					$this->Admin_Member_model->login_ok($data);
					$this->session->set_userdata(
						array(
							"admin_member" => array(
								"userid" => $get_data["userid"],
								"name" => $get_data["name"],
								"group" => $get_data["group"],
								"level" => $get_data["level"],
							)
						)
					);
				}

				$post = $this->input->post(null, true);
				if($post['idsave'] === 'ok') {
					$limit = time() + (86400 * 30);
					setcookie('remember_id', trim($post['userid']), $limit);
				} else {
					setcookie('remember_id', '', time() - 1);
				}

				if($this->input->post("return_url", true)) {
					redirect(base_url($this->input->post("return_url", true)));
				} else {
					redirect(base_url("admin/main"));
				}
			} else {
				$err = $this->form_validation->error_array();
				if(count($err) > 0) {
					$keys = array_keys($err);
					$error = $err[$keys[0]];
					throw new Exception($error);
				}
				throw new Exception("로그인 정보가 없습니다.");
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function logout() {
		$this->session->unset_userdata("admin_member");
		unset($_SESSION["admin_member"]);
		redirect("/admin");
	}

	
}

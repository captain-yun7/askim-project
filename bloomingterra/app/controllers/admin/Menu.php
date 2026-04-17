<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends ADMIN_Controller {
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
	public function main_image_slide() {
		try{
			$this->config->load("cfg_mainImageSlide");
			$reg = $this->input->post("reg", true);
			$this->load->library("form_validation");

			if(isset($reg)) {
				$conf = $this->input->post("conf", true);
				$oname_file = $this->input->post("oname_file", true);
				$fname_file = $this->input->post("fname_file", true);
				$link_file = $this->input->post("link_file", true);

				$type = array('pc', 'mobile');
				$language = $this->_site_language["support_language"];

				if(!isset($conf)) {
					throw new Exception("저장할 정보가 없습니다.");
				}

				$conf['files'] = array();
				if(is_array($oname_file) && !empty($oname_file)){
					if(is_array($oname_file['fixed'])){
						foreach($oname_file['fixed'] as $k => $v){
							if($oname_file['fixed'][$k] && $fname_file['fixed'][$k]){
								$conf['files']['fixed'][] = array(
									'oname'		=> $oname_file['fixed'][$k],
									'fname'		=> $fname_file['fixed'][$k],
									'link'		=> $link_file['fixed'][$k]
								);
							}
						}
					}
					if(is_array($oname_file['responsive'])){
						$responsive_length = 0;
						foreach($oname_file['responsive'] as $k => $v){
							foreach($v as $_k => $_v){
								if($oname_file['responsive'][$k][$_k] && $fname_file['responsive'][$k][$_k]){
									$conf['files']['responsive'][$k][] = array(
										'oname'		=> $oname_file['responsive'][$k][$_k],
										'fname'		=> $fname_file['responsive'][$k][$_k],
										'link'		=> $link_file['responsive'][$k][$_k]
									);
								}
							}
							if(count($v) > $responsive_length) $responsive_length = count($v);
						}
						$conf['files']['responsive_length'] = array_pad(array(), $responsive_length, null);
					}
				}


				$confData = $this->config->item("cfg_mainImageSlide");
				if(!$confData || !is_array($confData)){
					// config Data 초기화
					$confData = array();
					foreach($type as $k => $v) foreach($language as $_k => $_v) $confData[$v][$_k] = array();
				}

				if(empty($confData[$conf['type']]) || !is_array($confData[$conf['type']])){
					$confData[$conf['type']] = array();
				}

				if(empty($confData[$conf['type']][$conf['language']]) || !is_array($confData[$conf['type']][$conf['language']])){
					$confData[$conf['type']][$conf['language']] = array();
				}


				$confData[$conf['type']][$conf['language']] = array_merge($confData[$conf['type']][$conf['language']], $conf);

				unset($confData[$conf['type']][$conf['language']]['type'], $confData[$conf['type']][$conf['language']]['language']);
				// 모바일일 경우 고정형만 가능
				if($conf['type'] === 'mobile'){
					$confData[$conf['type']][$conf['language']]['form'] = 'fixed';
					unset($confData[$conf['type']][$conf['language']]['responsive']);
				}

				$set_data = "";
				$set_data .= "<?php\n";
				$set_data .= "defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
				$set_data .= "\$config = array(\n";
				$set_data .= "\t'cfg_mainImageSlide' => array(\n";
				$set_data .= $this->arrayPrint($confData);
				$set_data .= "\t),\n";
				$set_data .= ");\n";

				$this->load->library("qfile");
				$this->qfile->open(APPPATH."/config/cfg_mainImageSlide.php");
				$this->qfile->write($set_data);
				$this->qfile->close();

				msg("저장되었습니다", "main_image_slide");
			} else {
				$get_data = array();
				$get_data["conf"] = $this->config->item("cfg_mainImageSlide");
				$this->set_view("admin/menu/main_image_slide", $get_data);
			}
		} catch(Exception $e) {
			msg($e->getMessage(), -1);
		}
	}

	public function arrayPrint($val, $depth = 0){
		$tab = str_repeat('	', ($depth+2));
		if(is_array($val)){
			foreach($val as $k => $v){
				if(is_array($v)){
					$str .= $tab . "'" . $k . "' " . "=> array(\n";
					$str .= $this->arrayPrint($v, $depth+1);
					$str .= $tab . "),\n";
				} else {
					$str .= $tab . "'" . $k . "' => '" . $v . "',\n";
				}
			}
		}
		return $str;
	}
}

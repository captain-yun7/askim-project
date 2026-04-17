<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/third_party/PHPMailer/PHPMailerAutoload.php';

class Sendemail extends PHPMailer {
    private $ci;

    public function __construct() {
        parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('Database_model', 'dm');
        $smtp = $this->ci->dm->get('da_manage')[0];
        $this->isSMTP();
        $this->SMTPAuth = true;
        $this->Host = $smtp['smtp_host'];
        $this->Username = $smtp['smtp_userid'];
        $this->Password = $smtp['smtp_password'];
        $this->SMTPSecure = $smtp['smtp_secure'];
        $this->Port = $smtp['smtp_port'];
        $this->setFrom($smtp['smtp_userid'], $smtp['smtp_send_name']);
        $this->isHTML(true);
        $this->CharSet = 'utf8';
        $this->Encoding = 'base64';
        $this->SMTPDebug = 4;
    }

    /*
     * 필수 : $data['mailto']['email'], $data['mailto']['name']
     * $data['skin'], $data['subject']
     * 실제 사용하는 곳에서 따로 스킨, 메일 제목을 지정할 경우 우선 적용
     * 그렇지 않을 경우 da_mailskins 테이블에 types로 검색해서 나오는 스킨, 메일 제목 사용
     * $data['mail_body'], $data['skin'] 두 변수는 쌍으로 같이 존재해야 함
     */
    public function setup($post) {
        $data = $post;
        $get_info = $this->ci->dm->get('da_mailskins', [], ['language' => $data['language'], 'types' => $data['type']])[0];
        $this->addAddress($data['mailto']['email'], $data['mailto']['name']);
        if(isset($data['subject'])) {
            $this->Subject = $data['subject'];
        } else {
            $data['subject'] = $get_info['subject'];
            $this->Subject = $this->_subject($data);
        }

        if(!isset($data['skin'])) $data['skin'] = FCPATH.'data/mail_form/'.$data['language'].'/'.$get_info['file'];
        $this->Body = $data['mail_body'] ? $data['mail_body'] : $this->_body($data);
        if($data['attachment']) $this->mail_attachment($data['attachment']);

        $this->send();
        $this->clearAddresses();
    }

    private function _subject($data) {
        $temp_subject = $data['subject'];
        $subject = [
            'name' => $data['extrainfos']['user_name'],
            'board_name' => $data['extrainfos']['board_name'],
            'category' => $data['extrainfos']['category'],
            'title' => $data['extrainfos']['title']
        ];
        foreach($subject as $key => $value) {
            $temp_subject = str_replace("{\$".$key."}", $value, $temp_subject);
        }
        return $temp_subject;
    }

    private function _body($data) {
        ob_start();
        include $data['skin'];
        $content = ob_get_contents();
        ob_end_clean();

        $cfg_site = $this->ci->config->config['cfg_site'][$data['language']];
        $copyright = [
            'nameKor' => $cfg_site['nameKor'],
            'address' => '['.$cfg_site['zipcode'].']'.$cfg_site['address'],
            'ceoName' => $cfg_site['ceoName'],
            'compSerial' => $cfg_site['compSerial'],
            'compFax' => $cfg_site['compFax'],
            'compPhone' => $cfg_site['compPhone'],
            'adminEmail' => $cfg_site['adminEmail'],
            'nameEng' => $cfg_site['nameEng'],
            'base_url' => base_url(),
        ];
        $temp = array_merge($data['extrainfos'], $copyright);
        foreach($temp as $key => $value) {
            $content = str_replace("{\$".$key."}", $value, $content);
        }
        return $content;
    }

    /**
     * @param $files
     * $files['rename'] = 첨부할 '업로드된 파일의 절대 경로/변경된 파일명'
     * $files['original'] = 첨부할 '업로드된 파일의 원본명'
     */
    private function mail_attachment($files) {
        if(count($files) > 0) {
            foreach($files as $key => $file) {
                $this->addAttachment($file['rename'], $file['original']);
            }
        }
    }
}
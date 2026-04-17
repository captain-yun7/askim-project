<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Instagram {
	protected $json;
	protected $saved;
	protected $CI;
	protected $token;
	protected $update;

	public function __construct($temp = []) {
		$this->json = APPPATH.'/config/json_instagram.json';
		$this->CI =& get_instance();
		$this->CI->load->library('qfile');
		$this->CI->qfile->open($this->json);
		$this->saved = json_decode($this->CI->qfile->read($this->json));
		$this->token = $temp['token'] ? $temp['token'] : $this->saved->token;
		$this->update = $temp['update'];
	}

	protected function request($url) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_SSL_VERIFYPEER => false,
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response, true);
	}

	protected function update_json($exception = []) {
		$data = [];
		$keys = ['yn_use', 'url', 'feed_limit', 'data', 'last_data_update', 'token', 'last_token_update'];
		foreach($keys as $v) {
			if($v == $exception[0]) {
				$data[$exception[0]] = $exception[1];
			} else {
				$data[$v] = $this->saved->{$v};
			}
		}
		if($exception[0] == 'data') $data['last_data_update'] = date('Y-m-d H:i:s');

		$this->CI->qfile->open($this->json);
		$this->CI->qfile->write(json_encode($data));
		$this->CI->qfile->close();
	}

	protected function refresh_token() {
		$diff = time() - strtotime($this->saved->last_token_update);
		if($diff > 86400) {
			$url_refresh = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$this->token;
			$this->request($url_refresh);
			$this->update_json(['last_token_update', date('Y-m-d H:i:s')]);
		}
	}

	protected function update_feed() {
		$data = [];
		$url_feed = 'https://graph.instagram.com/me/media?fields=username,permalink,media_url,thumbnail_url,timestamp,caption&access_token='.$this->token;
		$feed = $this->request($url_feed)['data'];
		for($i = 0; $i < 10; $i++) {
			$data[] = $feed[$i];
		}
		$this->update_json(['data', $data]);
	}

	public function get_my_feed() {
		$this->refresh_token();
		$diff = time() - strtotime($this->saved->last_data_update);
		if($this->update == 'f') $this->update_feed();
		if($diff > 3600) $this->update_feed();

		$this->CI->qfile->open($this->json);
		$this->saved = json_decode($this->CI->qfile->read($this->json));
		return $this->saved->data;
	}
}
<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Alter extends ADMIN_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function alter_board_table()
	{
		$arrTable = array(
			'da_board_comment',
			'da_board_gallery',
			'da_board_notice',
			'da_board_review',
			'da_board_inquiry',
		);

		$standardSQL = "ALTER TABLE tableName MODIFY password VARCHAR(200)";
		foreach($arrTable as $tblNm){
			$alterSQL = str_replace('tableName', $tblNm, $standardSQL);
			$result = $this->db->query($alterSQL);

			if($result === true){
				debug($tblNm.' 테이블 수정 성공');
			}else{
				debug($tblNm.' 테이블 수정 실패');
			}
		}
	}

	public function alter_goods_table()
	{
		//$alterSQL = "ALTER TABLE da_goods MODIFY info text";
		//$result = $this->db->query($alterSQL);
		//if($result === true){
			//debug('상품 테이블 수정 성공');
		//}else{
			//debug('상품 테이블 수정 실패');
		//}
	}

	public function test()
	{
		exec($_SERVER['DOCUMENT_ROOT'].'/package.sh', $res);
		echo join($res, "\n");

	}
}
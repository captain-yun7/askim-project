<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Alter extends FRONT_Controller {
	public function __construct() {
		parent::__construct();
	}


	public function alter_table()
	{

		# 회원 관련 Start
		$result = $this->db->simple_query("ALTER TABLE da_member MODIFY language VARCHAR(30) DEFAULT 'kor'");

		if($result == false){
			print_r("da_member language 수정성공<br/>");
		}else{
			print_r("da_member language 수정실패<br/>");
		}

		$result = $this->db->simple_query("ALTER TABLE da_member DROP PRIMARY KEY");

		if($result == false){
			print_r("da_member userid 수정성공<br/>");
		}else{
			print_r("da_member userid 수정실패<br/>");
		}

		$result = $this->db->simple_query("ALTER TABLE da_member ADD PRIMARY KEY (language, userid)");

		if($result == false){
			print_r("da_member userid, language 수정성공<br/>");
		}else{
			print_r("da_member userid, language 수정실패<br/>");
		}

		$result = $this->db->simple_query("ALTER TABLE da_dormant DROP PRIMARY KEY");

		if($result == false){
			print_r("da_dormant userid 수정성공<br/>");
		}else{
			print_r("da_dormant userid 수정실패<br/>");
		}

		$result = $this->db->simple_query("ALTER TABLE da_dormant ADD PRIMARY KEY (language, userid)");

		if($result == false){
			print_r("da_dormant userid, language 수정성공<br/>");
		}else{
			print_r("da_dormant userid, language 수정실패<br/>");
		}

		$result = $this->db->simple_query("ALTER TABLE da_member ADD country VARCHAR(100) DEFAULT NULL COMMENT '국가명', ADD city VARCHAR(100) DEFAULT NULL COMMENT '도시명', ADD state_province_region VARCHAR(100) DEFAULT NULL COMMENT '주/도명', ADD mobile_country_code VARCHAR(100) DEFAULT NULL COMMENT '국가코드';");

		if($result == false){
			print_r("da_member country, city, state_province_region, mobile_country_code 추가성공<br/>");
		}else{
			print_r("da_member country, city, state_province_region, mobile_country_code 추가실패<br/>");
		}

		$result = $this->db->simple_query("ALTER TABLE da_dormant ADD country VARCHAR(100) DEFAULT NULL COMMENT '국가명', ADD city VARCHAR(100) DEFAULT NULL COMMENT '도시명', ADD state_province_region VARCHAR(100) DEFAULT NULL COMMENT '주/도명', ADD mobile_country_code VARCHAR(100) DEFAULT NULL COMMENT '국가코드';");

		if($result == false){
			print_r("da_dormant country, city, state_province_region, mobile_country_code 추가성공<br/>");
		}else{
			print_r("da_dormant country, city, state_province_region, mobile_country_code 추가실패<br/>");
		}
		# 회원 관련 End
		# 게시판 관련 Start
		$arrExceptTable = array(
			'da_board_file',
			'da_board_global',
			'da_board_manage',
		);

		$strSQL = "SHOW TABLES";
		$getData = $this->db->query($strSQL)->result_array();
		$tableData = array();
		foreach($getData as $fkey => $fval){
			foreach($fval as $skey => $sval){
				if(!in_array($sval, $arrExceptTable) && strpos($sval, 'da_board_') !== false){
					$tableData[] = $sval;
				}
			}
		}
		$chkExist = $this->db->query("SHOW COLUMNS FROM da_board_manage LIKE 'extraFl'")->row_array();
		if(empty($chkExist)){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_board_manage ADD extraFl enum('n', 'y') DEFAULT 'n' COMMENT '추가필드 사용여부'");
			}catch(\Exception $e){
				$result = false;
			}

			if($result === true){
				print_r("da_board_manage extraFl 컬럼 추가 성공<br/>");
			}else {
				print_r("da_board_manage extraFl 컬럼 추가 실패</br>");
			}
		}else {
			print_r("da_board_manage extraFl 컬럼이 이미 존재함<br/>");
		}

		$chkExist = $this->db->query("SHOW COLUMNS FROM da_board_manage LIKE 'extra_file_size'")->row_array();
		if(empty($chkExist)){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_board_manage ADD extra_file_size int(11) DEFAULT NULL COMMENT '추가필드 파일 제한 용량'");
			}catch(\Exception $e){
				$result = false;
			}

			if($result === true){
				print_r("da_board_manage extra_file_size 컬럼 추가 성공<br/>");
			}else {
				print_r("da_board_manage extra_file_size 컬럼 추가 실패</br>");
			}
		}else {
			print_r("da_board_manage extra_file_size 컬럼이 이미 존재함<br/>");
		}

		$chkExist = $this->db->query("SHOW COLUMNS FROM da_board_manage LIKE 'extraFieldInfo'")->row_array();
		if(empty($chkExist)){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_board_manage ADD extraFieldInfo TEXT DEFAULT NULL COMMENT '추가필드 정보'");
			}catch(\Exception $e){
				$result = false;
			}

			if($result === true){
				print_r("da_board_manage extraFieldInfo 컬럼 추가 성공<br/>");
			}else {
				print_r("da_board_manage extraFieldInfo 컬럼 추가 실패</br>");
			}
		}else {
			print_r("da_board_manage extraFieldInfo 컬럼이 이미 존재함<br/>");
		}

		foreach($tableData as $tableNm){
			$chkExist = $this->db->query(sprintf("SHOW COLUMNS FROM %s LIKE 'extraFieldInfo'", $tableNm))->row_array();
			if(empty($chkExist)) {
				$alterSQL = sprintf("ALTER TABLE %s ADD extraFieldInfo TEXT DEFAULT NULL COMMENT '추가필드 정보'", $tableNm);
				try{
					$result = $this->db->simple_query($alterSQL);
				}catch(\Exception $e){
					$result = false;
				}
				if($result === true){
					print_r(sprintf("%s 테이블 extraFieldInfo 컬럼 추가 성공<br/>", $tableNm));
				}else{
					print_r(sprintf("%s 테이블 extraFieldInfo 컬럼 추가 실패<br/>", $tableNm));
				}
			}else {
				print_r(sprintf("%s 테이블 extraFieldInfo 컬럼이 이미 존재함<br/>", $tableNm));
			}
		}

		foreach($tableData as $tableNm){
			$chkExist = $this->db->query(sprintf("SHOW COLUMNS FROM %s LIKE 'content'", $tableNm))->row_array();
			if(empty($chkExist)) {
				print_r(sprintf("%s 테이블 content 컬럼이 없으므로 수정 X<br/>", $tableNm));
			}else {
				if($chkExist['Type'] != 'mediumtext') {
					$alterSQL = sprintf("ALTER TABLE %s MODIFY content MEDIUMTEXT DEFAULT NULL", $tableNm);
					try{
						$result = $this->db->simple_query($alterSQL);
					}catch(\Exception $e){
						$result = false;
					}
					if($result === true){
						print_r(sprintf("%s 테이블 content 컬럼 수정 성공<br/>", $tableNm));
					}else{
						print_r(sprintf("%s 테이블 content 컬럼 수정 실패<br/>", $tableNm));
					}
				}
			}
		}

		foreach($tableData as $tableNm){
			$chkExist = $this->db->query(sprintf("SHOW COLUMNS FROM %s LIKE 'answer_content'", $tableNm))->row_array();
			if(empty($chkExist)) {
				print_r(sprintf("%s 테이블 `answer_content` 컬럼이 없으므로 수정 X<br/>", $tableNm));
			}else {
				if($chkExist['Type'] != 'mediumtext') {
					$alterSQL = sprintf("ALTER TABLE %s MODIFY `answer_content` MEDIUMTEXT DEFAULT NULL", $tableNm);
					try{
						$result = $this->db->simple_query($alterSQL);
					}catch(\Exception $e){
						$result = false;
					}
					if($result === true){
						print_r(sprintf("%s 테이블 `answer_content` 컬럼 수정 성공<br/>", $tableNm));
					}else{
						print_r(sprintf("%s 테이블 `answer_content` 컬럼 수정 실패<br/>", $tableNm));
					}
				}
			}
		}
		# 게시판 관련 End

		# 상품 관련 Start
		for($idx=1;$idx<=20; $idx++) {
			$columnNm = "ex".$idx;
			// 해당 컬럼이 존재하는지 확인
			$chkExist = $this->db->query("SHOW COLUMNS FROM da_goods_multi LIKE '".$columnNm."'")->row_array();
			if(empty($chkExist)){
				try{
					$result = $this->db->simple_query("ALTER TABLE da_goods_multi ADD ".$columnNm." VARCHAR(500) DEFAULT NULL");
				}catch(\Exception $e){
					$result = false;
				}

				if($result === true){
					print_r("da_goods_multi 테이블 ".$columnNm." 컬럼 추가 성공<br/>");
				}else{
					print_r("da_goods_multi 테이블  ".$columnNm." 컬럼 추가 실패<br/>");
				}
			}else{
				print_r("da_goods_multi 테이블  ".$columnNm." 컬럼이 이미 존재함<br/>");
			}
		}

		//2020-03-05 Inbet Matthew da_goods_multi 테이블 info 컬럼 varchar 2000 에서 text로 수정함
		$alterSQL = "ALTER TABLE da_goods MODIFY info text,
		MODIFY sortNum int(11),
		MODIFY ex1 text,
		MODIFY ex2 text,
		MODIFY ex3 text,
		MODIFY ex4 text,
		MODIFY ex5 text,
		MODIFY ex6 text,
		MODIFY ex7 text,
		MODIFY ex8 text,
		MODIFY ex9 text,
		MODIFY ex10 text,
		MODIFY ex11 text,
		MODIFY ex12 text,
		MODIFY ex13 text,
		MODIFY ex14 text,
		MODIFY ex15 text,
		MODIFY ex16 text,
		MODIFY ex17 text,
		MODIFY ex18 text,
		MODIFY ex19 text,
		MODIFY ex20 text
		";
		$result = $this->db->query($alterSQL);
		if($result === true){
			print_r('da_goods 테이블 수정 성공');
		}else{
			print_r('da_goods 테이블 수정 실패');
		}

		$alterSQL = "ALTER TABLE da_goods_multi MODIFY info text,
		MODIFY ex1 text,
		MODIFY ex2 text,
		MODIFY ex3 text,
		MODIFY ex4 text,
		MODIFY ex5 text,
		MODIFY ex6 text,
		MODIFY ex7 text,
		MODIFY ex8 text,
		MODIFY ex9 text,
		MODIFY ex10 text,
		MODIFY ex11 text,
		MODIFY ex12 text,
		MODIFY ex13 text,
		MODIFY ex14 text,
		MODIFY ex15 text,
		MODIFY ex16 text,
		MODIFY ex17 text,
		MODIFY ex18 text,
		MODIFY ex19 text,
		MODIFY ex20 text
		";
		$result = $this->db->query($alterSQL);
		if($result === true){
			print_r('da_goods_multi 테이블 수정 성공');
		}else{
			print_r('da_goods_multi 테이블 수정 실패');
		}
		//Matthew 끝

		if(!$this->db->field_exists("access_auth", "da_category")){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_category ADD access_auth INT(11) DEFAULT NULL");
			}catch(\Exception $e){
				$result = false;
			}

			if($result == true){
				print_r("da_goods_multi 테이블 access_auth 컬럼 추가 성공<br/>");
			}else {
				print_r("da_goods_multi 테이블 access_auth 컬럼 추가 실패<br/>");
			}
		}else{
			print_r("da_goods_multi 테이블 access_auth 컬럼이 이미 존재함<br/>");
		}

		# 상품 관련 End

		# popup 관련 Start
		$arr = array(
			"pc" => "pc",
			"mobile" => "모바일",
			"responsive_pc" => "반응형 pc",
			"responsive_tablet" => "반응형 테블릿",
			"responsive_mobile" => "반응형 모바일",
		);

		$arrQuery = array(
			"topptx_%s" => "ALTER TABLE da_popup ADD toppx_%s INT(11) DEFAULT 0 COMMENT '%s 상단간격'",
			"leftpx_%s" => "ALTER TABLE da_popup ADD leftpx_%s INT(11) DEFAULT 0 COMMENT '%s 좌측간격'",
			"width_%s" => "ALTER TABLE da_popup ADD width_%s INT(11) DEFAULT 0 COMMENT '%s 너비'",
			"height_%s" => "ALTER TABLE da_popup ADD height_%s INT(11) DEFAULT 0 COMMENT '%s 높이'",
		);

		foreach($arr as $key => $val) {
			foreach($arrQuery as $columnKey => $queryVal){
				if(!$this->db->field_exists(sprintf($columnKey, $key), 'da_popup')){
					$alterSQL = sprintf($queryVal, $key, $val);
				}else{
					print_r(sprintf("da_popup 테이블 ".$columnKey." 컬럼이 이미 존재함<br/>", $key));
				}
			}
		}

		$chkExist = $this->db->query("SHOW COLUMNS FROM da_popup LIKE 'content'")->row_array();
		if(empty($chkExist)) {
			print_r("da_popup 테이블 `content` 컬럼이 없으므로 수정 X<br/>");
		}else {
			if($chkExist['Type'] != 'text') {
				$alterSQL = sprintf("ALTER TABLE %s MODIFY `content` TEXT DEFAULT NULL", "da_popup");
				try{
					$result = $this->db->simple_query($alterSQL);
				}catch(\Exception $e){
					$result = false;
				}
				if($result === true){
					print_r(sprintf("%s 테이블 `content` 컬럼 수정 성공<br/>", "da_popup"));
				}else{
					print_r(sprintf("%s 테이블 `content` 컬럼 수정 실패<br/>", "da_popup"));
				}
			}else{
				print_r(sprintf("%s 테이블 `content` 컬럼 이미 text 타입이므로 수정 X<br/>", "da_popup"));
			}
		}

		$chkExist = $this->db->query("SHOW COLUMNS FROM da_popup LIKE 'recognition_pc'")->row_array();
		if(empty($chkExist)){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_popup ADD recognition_pc INT(11) DEFAULT 0 COMMENT '반응형 pc 인식넓이'");
			}catch(\Exception $e){
				$result = false;
			}

			if($result === true){
				print_r("da_popup 테이블 recognition_pc 컬럼 추가 성공<br/>");
			}else{
				print_r("da_popup 테이블  recognition_pc 컬럼 추가 실패<br/>");
			}
		}else{
			print_r("da_popup 테이블  recognition_pc 컬럼이 이미 존재함<br/>");
		}

		$chkExist = $this->db->query("SHOW COLUMNS FROM da_popup LIKE 'recognition_tablet'")->row_array();
		if(empty($chkExist)){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_popup ADD recognition_tablet INT(11) DEFAULT 0 COMMENT '반응형 pc 인식넓이'");
			}catch(\Exception $e){
				$result = false;
			}

			if($result === true){
				print_r("da_popup 테이블 recognition_tablet 컬럼 추가 성공<br/>");
			}else{
				print_r("da_popup 테이블  recognition_tablet 컬럼 추가 실패<br/>");
			}
		}else{
			print_r("da_popup 테이블  recognition_tablet 컬럼이 이미 존재함<br/>");
		}

		if(!$this->db->field_exists("popupform", "da_popup")){
			try{
				$result = $this->db->simple_query("ALTER TABLE da_popup ADD popupform enum('fixed', 'responsive') DEFAULT 'fixed' COMMENT '팝업 형식'");
			}catch(\Exception $e){
				$result = false;
			}

			if($result === true){
				print_r("da_popup 테이블 popupform 컬럼 추가 성공<br/>");
			}else{
				print_r("da_popup 테이블  popupform 컬럼 추가 실패<br/>");
			}
		}else {
			print_r("da_popup 테이블  popupform 컬럼이 이미 존재함<br/>");
		}

		# popup 관련 End

		# 이용약관 수정 Start
		$result = $this->db->simple_query("UPDATE da_terms SET code = 'agreement' WHERE title = '기업 이용약관 동의'");
		if($result == true){
			print_r("da_terms 기업 이용약관 동의 수정 완료<br/>");
		}else {
			print_r("da_terms 기업 이용약관 동의 수정 실패<br/>");
		}

		$result = $this->db->simple_query("UPDATE da_terms SET code= 'usePolicy' WHERE title = '개인정보 수집 및 이용에 대한 안내'");
		if($result == true){
			print_r("da_terms 개인정보 수집 및 이용에 대한 안내 수정 완료<br/>");
		}else {
			print_r("da_terms 개인정보 수집 및 이용에 대한 안내 수정 실패<br/>");
		}
		# 이용약관 수정 End
	}

	public function alter_goods_table()
	{
		$alterSQL = "ALTER TABLE da_goods MODIFY info text";
		$result = $this->db->query($alterSQL);
		if($result === true){
			print_r('상품 테이블 수정 성공');
		}else{
			print_r('상품 테이블 수정 실패');
		}
	}
}
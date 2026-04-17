<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$browser = getBrowser2();

if($browser['name']=="Google Chrome"){
	$name = $_COOKIE['facebook_username'];
}else{
	$name = iconv("EUC-KR","UTF-8",$_COOKIE['facebook_username']);
}

if(!empty($_COOKIE['facebook_id'])){

	$_SESSION['wiz_session']['id']          = "FB".$_COOKIE['facebook_id'];
	$_SESSION['wiz_session']['passwd']      = $_COOKIE['facebook_id'];
	$_SESSION['wiz_session']['name']        = $name;
	$_SESSION['wiz_session']['email']       = $_COOKIE['facebook_email'];
	$_SESSION['wiz_session']['login_Type']  = $_COOKIE['login_Type'];
	$_SESSION['wiz_session']['sns_login']   = $_COOKIE['sns_login'];	

	if($site_info['denyid'] != ""){

		if($site_info['denyiduse'] == "Y"){

			$deny_id = false;
			$id_patt = explode("\n", trim($site_info['denyid']));
			for($i=0; $i<count($id_patt); $i++){
				$id_patt[$i] = trim($id_patt[$i]);
				if(empty($id_patt[$i])) continue;

				//$id_patt[$i] = str_replace(".", "\.", $id_patt[$i]);
				//$id_patt[$i] = str_replace("*", "[0-9\.]*", $id_patt[$i]);
				$pattern = "/^{$id_patt[$i]}$/";
				$deny_id = preg_match($pattern, $_SESSION['wiz_session']['id']);

				if($deny_id == true) {
					session_destroy();
					$msg = "사이트접근이 제한되었습니다.";
					echo "<script>alert('$msg');opener.location='/';self.close();</script>";
				}
				
			}

		}

	}

	if($deny_id == true) {

	} else {

		$mem_sql     = "SELECT hphone,tphone,level,name,email FROM wiz_member WHERE id='".$_SESSION['wiz_session']['id']."'";
		$mem_result  = mysql_query($mem_sql);
		$mem_info    = mysql_fetch_array($mem_result);

		$level_info  = level_info();
		$level       = $mem_info['level'];
		$level_value = $level_info[$level][level];

		$_SESSION['wiz_session']['name']        = $mem_info['name'];
		$_SESSION['wiz_session']['email']       = $mem_info['email'];
		$_SESSION['wiz_session']['hphone']      = $mem_info['hphone'];
		$_SESSION['wiz_session']['tphone']      = $mem_info['tphone'];
		$_SESSION['wiz_session']['level']       = $level;
		$_SESSION['wiz_session']['level_value'] = $level_value;
		$_SESSION['wiz_session']['wiz_basket_id'] = $_SESSION['wiz_session']['id'];

		$sql = "update wiz_member set visit = visit+1 , visit_time = now() where id='".$_SESSION['wiz_session']['id']."'";
		$result = mysql_query($sql);

		//장바구니업데이트		
		$tmp_basket_idx = "";
		$tmp_sql = "select idx from wiz_basket_tmp where uniq_id = '".$uniq_id."' ";
		$tmp_res = query($tmp_sql);
		while($tmp_row = sql_fetch_arr($tmp_res)) {
			$tmp_basket_idx[] = $tmp_row['idx'];
		}

		$uproduct_idx = implode("|", $tmp_basket_idx);

		if($uproduct_idx != ""){

			$product_idx_arr = explode("|",$uproduct_idx);
			$basket_uniq_id = md5($_SESSION['wiz_session']['wiz_basket_id']);

			for($kk=0; $kk<count($product_idx_arr); $kk++){
				$sql_up = "
					update wiz_basket_tmp 
					   set uniq_id='$basket_uniq_id'
						 , memid='".$_SESSION['wiz_session']['id']."' 
					 where idx='$product_idx_arr[$kk]'
				";
				query($sql_up);
			}

		}
 
		if($_COOKIE['prev_page']=="") $_COOKIE['prev_page'] = "/";
		if($_COOKIE['prev_page']=="" && mobile_check() || $_COOKIE['PM'] == "M") $_COOKIE['prev_page'] = "/m";

		if($_COOKIE['PM']=="M" || $_COOKIE['prev_page'] == "/m"){

		echo "
			<script type='text/javascript'>
			document.location=\"$_COOKIE['prev_page']\"; 
			var pdate = new Date();
			pdate.setSeconds(pdate.getSeconds()-1);
			document.cookie = 'prev_page=\"\"; expires='+pdate.toGMTString()+'; path=/';
			</script>";

		}else{

		echo "<script> 
			var pdate = new Date();
			pdate.setSeconds(pdate.getSeconds()-1);
			document.cookie = 'prev_page=\"\"; expires='+pdate.toGMTString()+'; path=/';
			document.location='$_COOKIE['prev_page']'; 
			self.close();
			</script>";
		}

	}

}


?>

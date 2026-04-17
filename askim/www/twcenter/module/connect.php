<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if(!isset($_COOKIE["threeway"])) $_COOKIE["threeway"] = '';

if(strlen($_COOKIE["threeway"]) == 0 && check_robots($_SERVER['HTTP_USER_AGENT'])) {

	makeCookie("threeway", "true");

	if((version_compare(phpversion(), "5.3.0", ">=") && $site_info['browscap'] == "Y")){

		include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/connect_lib.php";

	} else {

		$browser    = getBrowser_default();
		$os         = getOs_default();
		$tmp_device = getUserAgent();
		if($tmp_device == 'ios' || $tmp_device == 'android' ||  $tmp_device == 'etc') {
			$device = "Mobile(".$tmp_device.")";
		} else {
			$device = "PC";
		}

		$wdate      = date("Y-m-d");
		$wtime      = date("H:i:s");

	}

	$referer = $_SERVER['HTTP_REFERER'];
	$parse_url = parse_url($referer);
	$host = $parse_url['host'];

	$ip   = $_SERVER['REMOTE_ADDR'];
	$time = date('YmdH');

	$c_sql = "
		select count(*) as cnt 
		  from wiz_con_total 
		 where ip = '".$ip."' 
		   and wdate = '".$wdate."' 
		   and os = '".$os."' 
		   and browser = '".$browser."' 
	";
	$c_res = query($c_sql);
	$c_row = sql_fetch_arr($c_res);
	$con_cnt = $c_row['cnt'];

	if($con_cnt > 0) {
		$sql = "
			update wiz_con_total 
			   set cnt = cnt+1 
			 where ip = '".$ip."' 
			   and wdate = '".$wdate."' 
			   and os = '".$os."' 
			   and browser = '".$browser."' 
		";
	} else {

		$sql_com = "";
		$sql_com .= " os                       = '$os'                            ";
		$sql_com .= " , browser                = '$browser'                       ";
		$sql_com .= " , referer                = '$referer'                       ";
		$sql_com .= " , host                   = '$host'                          ";
		$sql_com .= " , device                 = '$device'                        ";
		$sql_com .= " , cnt                    = '1'                              ";
		$sql_com .= " , wdate                  = '$wdate'                         ";
		$sql_com .= " , wtime                  = '$wtime'                         ";
		$sql_com .= " , time                   = '$time'                          ";
		$sql_com .= " , ip                     = '$ip'                            ";

		$sql = "insert into wiz_con_total set {$sql_com} ";

	}
	query($sql);

	// 검색키워드 확인 용 referer 저장
	if(!isset($host)) $host = '';
	if(strcmp($host, $_SERVER['HTTP_HOST'])) { 

		$wdate      = date("Y-m-d");
		$wtime      = date("H:i:s");

		$sql = "select referer from wiz_conrefer where wdate = '$wdate' and referer = '$referer'";
		$result = @query($sql);

		if(@sql_fetch_row($result) > 0){
			$sql = "update wiz_conrefer set cnt = cnt + 1 where  wdate = '$wdate' and referer = '$referer'";
			@query($sql);
		}else{
			$sql = "insert into wiz_conrefer(referer,host,wdate,wtime,cnt) values('$referer','$host','$wdate','$wtime', 1)";
			@query($sql);

		}

	}
	
	//counter 추가
	$time = date('YmdH');
	$sql = "select count(*) as total from wiz_contime where time = '$time'";
	$row = sql_fetch($sql);

	if($row['total'] > 0){
		$sql = "update wiz_contime set cnt = cnt + 1 where time = '$time'";
	}else{
		$sql = "insert into wiz_contime(time,cnt) values('$time',1)";
	}
	query($sql);
}

?>
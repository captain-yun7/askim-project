<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
$sql = "select * from wiz_meminfo";
$result = query($sql) or error("sql_error");
$exist = sql_fetch_row($result);

for($ii=0; $ii<count($info_use); $ii++){
	$infouse .= $info_use[$ii]."/";
}
for($ii=0; $ii<count($info_ess); $ii++){
   $infoess .= $info_ess[$ii]."/";
}
for($ii=0; $ii<count($add_name); $ii++){
   $addname .= $add_name[$ii]."|";
}

$method              = isset($_POST['method']) ? implode("/", $_POST['method']) : "";
$release_sleep_type  = isset($_POST['release_sleep_type']) ? implode("/", $_POST['release_sleep_type']) : "";

if($exist > 0){
	//agreement='$agreement', safeinfo='$safeinfo', 
	$sql = "
	
		update wiz_meminfo set 
		
			skin               = '$skin'           ,
			m_skin             = '$m_skin'         ,
			infouse            = '$infouse'        ,
			infoess            = '$infoess'        ,
			join_url           = '$join_url'       ,
			login_url          = '$login_url'      ,
			idpw_url           = '$idpw_url'       ,
			myinfo_url         = '$myinfo_url'     ,
			out_url            = '$out_url'        ,
			login_img          = '$login_img'      ,
			logout_img         = '$logout_img'     ,
			join_img           = '$join_img'       ,
			myinfo_img         = '$myinfo_img'     ,
			job_list           = '$job_list'       ,
			sch_list           = '$sch_list'       ,
			income_list        = '$income_list'    ,
			consph_list        = '$consph_list'    ,
			addname            = '$addname'        ,
			method             = '$method'         ,
			prohibit_id        = '$prohibit_id'    ,
			sdigit             = '$sdigit'         ,
			edigit             = '$edigit'         ,
			release_sleep_type = '$release_sleep_type'
			
		";
}else{
	$sql = "
	
		insert into wiz_meminfo
		(
			skin                            ,
			m_skin                          ,
			agreement                       ,
			safeinfo                        ,
			infouse                         ,
			infoess                         ,
			join_url                        ,
			login_url                       ,
			idpw_url                        ,
			myinfo_url                      ,
			out_url                         ,
			login_img                       ,
			logout_img                      ,
			join_img                        ,
			myinfo_img                      ,
			job_list                        ,
			sch_list                        ,
			income_list                     ,
			consph_list                     ,
			addname                         ,
			method                          ,
			prohibit_id                     ,
			sdigit                          ,
			edigit                          ,
			release_sleep_type
			
		) values (

			'$skin'                         ,
			'$m_skin'                       ,
			'$agreement'                    ,
			'$safeinfo'                     ,
			'$infouse'                      ,
			'$infoess'                      ,
			'$join_url'                     ,
			'$login_url'                    ,
			'$idpw_url'                     ,
			'$myinfo_url'                   ,
			'$out_url'                      ,
			'$login_img'                    ,
			'$logout_img'                   ,
			'$join_img'                     ,
			'$myinfo_img'                   ,
			'$job_list'                     ,
			'$sch_list'                     ,
			'$income_list'                  ,
			'$consph_list'                  ,
			'$addname'                      ,
			'$method'                       ,
			'$prohibit_id'                  ,
			'$sdigit'                       ,
			'$edigit'                       ,
			'$release_sleep_type'
			
		)";
}

$result = query($sql) or error("sql_error");

$sql = "select idx,fprior from wiz_formfield where fidx = 'addinfo' order by fprior asc";
$result = query($sql) or error("sql_error");
while($row = sql_fetch_arr($result)) {
	
	$addfield[$row['fprior']] = $row['idx'];
	
}

for($ii = 1; $ii <= 5; $ii++) {
	
	$tmp_flist = "";
	
 	for($jj = 0; $jj < count(${'flist'.$ii}); $jj++) {
 		$tmp_flist .= ${'flist'.$ii}[$jj];
 		if($jj < count(${'flist'.$ii}) - 1) $tmp_flist .= "|";
 	}
 	
	if(!empty($addfield[$ii])) {
		$sql = "update wiz_formfield set ftype='".${'ftype'.$ii}."', fsize = '".${'fsize'.$ii}."', fnum = '".${'fnum'.$ii}."', flist='".$tmp_flist."' where idx = '".$addfield[$ii]."'";
	} else {
		$sql = "insert into wiz_formfield (idx,fidx,fprior,fname,ftype,fessen,fsize,fnum,fimg,flist) 
						values('','addinfo','$ii','','".${'ftype'.$ii}."','','".${'fsize'.$ii}."','".${'fnum'.$ii}."','','".$tmp_flist."')";
	}
	
	query($sql) or error("sql_error");
	
}

complete("수정되었습니다.","member_config.php");

?>
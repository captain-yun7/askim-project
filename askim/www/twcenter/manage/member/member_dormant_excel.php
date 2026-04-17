<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
	$filename = "member_info_".date('Ymd').".xls";
	$filename = iconv("utf-8","euc-kr",$filename);

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	$level_info = level_info();

	// 입력정보 사용여부
	$sql = "select infouse from wiz_meminfo";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$info_tmp = explode("/",$row['infouse']);
	for($ii=0; $ii<count($info_tmp); $ii++){
		$info_use[$info_tmp[$ii]] = true;
	}


$excel_data = "
	<table border='1'>
		<tr>
			<td align='center'>아이디</td>
			<td align='center'>비밀번호</td>
			<td align='center'>이름</td>
			<td align='center'>회원등급</td>
			<td align='center'>휴면전환일</td>
			";
			if($info_use['photo']       == true) $excel_data  .= "<td align='center'>회원사진</td>";
			if($info_use['resno']       == true) $excel_data  .= "<td align='center'>주민번호</td>";
			if($info_use['tphone']      == true) $excel_data  .= "<td align='center'>전화번호</td>";
			if($info_use['hphone']      == true) $excel_data  .= "<td align='center'>휴대폰</td>";
			if($info_use['comtel']      == true) $excel_data  .= "<td align='center'>회사전화</td>";
			if($info_use['email']       == true) $excel_data  .= "<td align='center'>이메일</td>";
			if($info_use['reemail']     == true) $excel_data  .= "<td align='center'>이메일 수신</td>";
			if($info_use['homepage']    == true) $excel_data  .= "<td align='center'>홈페이지</td>";
			if($info_use['address']     == true) $excel_data  .= "<td align='center'>주소</td>";
			if($info_use['birthday']    == true) $excel_data  .= "<td align='center'>생년월일</td>";
			if($info_use['marriage']    == true) $excel_data  .= "<td align='center'>결혼여부</td>";
			if($info_use['memorial']    == true) $excel_data  .= "<td align='center'>결혼기념일</td>";
			if($info_use['job']         == true) $excel_data  .= "<td align='center'>직업</td>";
			if($info_use['scholarship'] == true) $excel_data  .= "<td align='center'>학력</td>";
			if($info_use['consph']      == true) $excel_data  .= "<td align='center'>관심분야</td>";
			if($info_use['hobby']       == true) $excel_data  .= "<td align='center'>취미</td>";
			if($info_use['income']      == true) $excel_data  .= "<td align='center'>월평균소득</td>";
			if($info_use['car']         == true) $excel_data  .= "<td align='center'>자동차소유</td>";
			if($info_use['intro']       == true) $excel_data  .= "<td align='center'>자기소개</td>";
$excel_data .= "

			<td align='center'>관리자메모</td>
			<td align='center'>가입일</td>
		</tr>";

	if($slevel != "") $level_sql .= " and level = '$slevel'";
	if($searchopt != "") $search_sql .= " and $searchopt like '%$searchkey%'";


	$array_seluser = explode('|',$seluser);
	if(count($array_seluser)-1 > 0){

		$tmp_seluser = "";
		foreach($array_seluser as $key => $value){
			if(!empty($value)) $tmp_seluser .= " or id = '{$value}'";
		}
		$tmp_seluser = substr($tmp_seluser,3);
		$seuser_sql = " ({$tmp_seluser})";
	} else {
		$seuser_sql = "id != '' ";
	}

	$sql = "select * from wiz_member_dormancy where $seuser_sql $level_sql $search_sql order by idx desc";
	$result = query($sql) or error("sql error");
	while($row = sql_fetch_arr($result)){


$excel_data .= "

		<tr>
			<td align='center'>".$row['id']."</td>
			<td align='center'>".$row['passwd']."</td>
			<td align='center'>".$row['name']."</td>
			<td align='center'>".$level_info[$row['level']]['name']."</td>
			<td align='center'>".$row['dchange_date']."</td>
			";

		if($info_use['photo']       == true) $excel_data  .= "<td align='center'>".$row['photo']."</td>";
		if($info_use['resno']       == true) $excel_data  .= "<td align='center'>".$row['resno']."</td>";
		if($info_use['tphone']      == true) $excel_data  .= "<td align='center'>".$row['tphone']."</td>";
		if($info_use['hphone']      == true) $excel_data  .= "<td align='center'>".$row['hphone']."</td>";
		if($info_use['comtel']      == true) $excel_data  .= "<td align='center'>".$row['comtel']."</td>";
		if($info_use['email']       == true) $excel_data  .= "<td align='center'>".$row['email']."</td>";
		if($info_use['reemail']     == true) $excel_data  .= "<td align='center'>".$row['reemail']."</td>";
		if($info_use['homepage']    == true) $excel_data  .= "<td align='center'>".$row['homepage']."</td>";
		if($info_use['address']     == true) $excel_data  .= "<td align='center'>".$row['post']." ".$row['address1']." ".$row['address2']."</td>";
		if($info_use['birthday']    == true) $excel_data  .= "<td align='center'>".$row['birthday']."</td>";
		if($info_use['marriage']    == true) $excel_data  .= "<td align='center'>".$row['marriage']."</td>";
		if($info_use['memorial']    == true) $excel_data  .= "<td align='center'>".$row['memorial']."</td>";
		if($info_use['job']         == true) $excel_data  .= "<td align='center'>".$row['job']."</td>";
		if($info_use['scholarship'] == true) $excel_data  .= "<td align='center'>".$row['scholarship']."</td>";
		if($info_use['consph']      == true) $excel_data  .= "<td align='center'>".$row['consph']."</td>";
		if($info_use['hobby']       == true) $excel_data  .= "<td align='center'>".$row['hobby']."</td>";
		if($info_use['income']      == true) $excel_data  .= "<td align='center'>".$row['income']."</td>";
		if($info_use['car']         == true) $excel_data  .= "<td align='center'>".$row['car']."</td>";
		if($info_use['intro']       == true) $excel_data  .= "<td align='center'>".str_replace("\r\n", " ", $row['intro'])."</td>";

$excel_data .= "

			<td align='center'>".$row['memo']."</td>
			<td align='center'>".$row['wdate']."</td>
		</tr>";



	}

$excel_data .= "</table>";

echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> ";
echo $excel_data;

?>
<?
$bbs_code = "online";
$day = "";
$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;  
$total_day = date("t", mktime(0, 0, 0, $month, 1, $year));

$start_month	= $year."-".$month."-01";
$last_month = $year."-".$month."-".$total_day;

$bbs_url = "/twcenter/manage/bbs/list.php?ptype=input&mode=insert&code=online&pos=&code_page=&menucode=BBS";
$sch_url = "input.php?code=".$code."&".$menucodeParam."&sch_date=";
$view_url = "view.php?code=".$code."&".$menucodeParam."&idx=";

$sql = "
		select 
			*
		from 
			wiz_bbs 
		where 
			code='$code' 
			and addinfo1 <= '".$last_month."' and addinfo2 >= '".$start_month."'
		order by 
			unix_timestamp(addinfo1) asc
";
$result = query($sql);
$k = 0;
$schedule_list = array();
while($row = sql_fetch_arr($result)){
	for($i=strtotime($row["addinfo1"]);$i<=strtotime($row["addinfo2"]);$i = $i+86400){
		if($i >= strtotime($start_month) && $i <= strtotime($last_month)){
			if($schedule_list[date("j", $i)][$k] != "") $k++;
			$schedule_list[date("j", $i)][$k]["idx"] = $row["idx"];
			$schedule_list[date("j", $i)][$k]["subject"] = $row["subject"];
			$schedule_list[date("j", $i)][$k]["addinfo1"] = $row["addinfo1"];
			$schedule_list[date("j", $i)][$k]["addinfo2"] = $row["addinfo2"];
			$schedule_list[date("j", $i)][$k]["addinfo3"] = $row["addinfo3"];
			$schedule_list[date("j", $i)][$k]["addinfo4"] = $row["addinfo4"];
			$schedule_list[date("j", $i)][$k]["addinfo5"] = $row["addinfo5"];
			$schedule_list[date("j", $i)][$k]["addinfo6"] = $row["addinfo6"];
		}
	}
}

$sql = "
		select
			*
		from
			wiz_bbs
		where
			code = 'online'
			and addinfo2 BETWEEN '".$start_month."' AND '".$last_month."'
			and status != '3'
		order by
			unix_timestamp(addinfo2) asc, addinfo3 asc
";
$result = query($sql);
for($i=0;$row = sql_fetch_arr($result);$i++){
	$days = date("j", strtotime($row['addinfo2']));
	$sidx = $row['addinfo1'];
	$time = $row['addinfo3'];
	
	$account_list[$days][$sidx][$time]['idx'] = $row['idx'];
	$account_list[$days][$sidx][$time]['status'] = $row['status'];
	$account_list[$days][$sidx][$time]['addinfo1'] = $row['addinfo1']; // schedule inquiry idx
	$account_list[$days][$sidx][$time]['addinfo2'] = $row['addinfo2']; // 날짜
	$account_list[$days][$sidx][$time]['addinfo3'] = $row['addinfo3']; // 시간
	$account_list[$days][$sidx][$time]['tot_cnt'] += $row['addinfo4']; // 예약인원
	$k++;
}
if(!isset($_GET['items'])) $_GET['items'] = '';
$itemAr = explode("|", $_GET['items']);

$hsql = " select * from wiz_holiday where (holiday_date between '".$start_month."' and '".$last_month."') and holiday_type = '공휴일' ";
$hres = query($hsql);
$holidays = array();
while($hrow = sql_fetch_arr($hres)) {
	$holidays[] = $hrow['holiday_date'];
}

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style account_calendar">
	<tr height="25" align="center">
		<td width="14%" class="t_name"><b><font color=red>Sun</font></b></td>
		<td width="14%" class="t_name"><b>Mon</b></td>
		<td width="14%" class="t_name"><b>Tue</b></td>
		<td width="14%" class="t_name"><b>Wed</b></td>
		<td width="14%" class="t_name"><b>Thu</b></td>
		<td width="14%" class="t_name"><b>Fri</b></td>
		<td width="14%" class="t_name"><b><font color=blue>Sat</font></b></td>
	</tr>
	<?php
	if(($first_day > 5) && ((floor($total_day / 5) == 6) || (floor($total_day / 5) == 7))) {
		$line = 6;
		if($total_day == 30 && $first_day == 6)
			$line--;
	} else if(($first_day == 1) && ($total_day == 28)) {
		$line = 4;
	} else {
		$line = 5;
	}
	
	$k = 0;
	
	for ($i=1; $i<=$line; $i++) {
		echo "<tr height=80>";
		
		for ($j=1; $j<=7; $j++) {
			if ((!$day) && ($j == $first_day)) $day = 1;
			
			$date_color = "";
			$hcss = "";
			if ($j == 7) $date_color = "blue";
			if ($j == 1) $date_color = "red";
			
			if ($day > 0){
				$tmp_day = $day;
				
				if(!is_array($schedule_list[$tmp_day])) $schedule_list[$tmp_day] = array();

				if(!strcmp($day, date('j'))) $day = "<b>".$day."</b>";

				$wdate = $year."-".$month."-".sprintf('%02d', $tmp_day);

				if(in_array($wdate, $holidays)){
					$date_color = "red";
					$hcss = "holiday";
				}
	?>
		<td valign="top" bgcolor="#ffffff" class="verdana <?=$hcss?>">
			<a href="<?php echo $sch_url.$wdate; ?>">
				<font size="3" color="<?=$date_color?>"><?=$day?></font>
			</a>
			<br>
			<?php
			if($tmp_day == null) $tmp_day = "";
			if($tmp_day != "" && count($schedule_list[$tmp_day]) > 0){
				if(count($schedule_list[$tmp_day]) > 0){
					for($k=0;$k<=max(array_keys($schedule_list[$tmp_day]));$k++){
						$apply_cont = "";
						if($schedule_list[$tmp_day][$k]['idx'] != ""){
							if($schedule_list[$tmp_day][$k]['subject'] == "") $schedule_list[$tmp_day][$k]['subject'] = "제목없음";
							
							$apply_cont .= '<p class="apply_cont">';

							$apply_cont .= '<a href="'.$view_url.$schedule_list[$tmp_day][$k]['idx'].'"><span class="status cancel">일정수정</span></a>';

							if(in_array($tmp_day."_".$schedule_list[$tmp_day][$k]['idx'], $itemAr)) $addCss1 = "on";
							else $addCss1 = "";

							if($schedule_list[$tmp_day][$k]['addinfo5'] == "N"){
								$apply_cont .= '&nbsp;&nbsp;<a href="javascript:;" class="schedule_item red '.$addCss1.'" data-idx="'.$schedule_list[$tmp_day][$k]['idx'].'" data-tmpday="'.$tmp_day.'">'.$schedule_list[$tmp_day][$k]['subject'].'</a>';
							} else {
								$apply_cont .= '&nbsp;&nbsp;<a href="javascript:;" class="schedule_item '.$addCss1.'" data-idx="'.$schedule_list[$tmp_day][$k]['idx'].'" data-tmpday="'.$tmp_day.'">'.$schedule_list[$tmp_day][$k]['subject'].'</a>';
							}
							

							$apply_cont .= '</p>';

							if($schedule_list[$tmp_day][$k]['addinfo3'] != ""){
								$time_array = array();
								$time_array = explode("/", $schedule_list[$tmp_day][$k]['addinfo3']);
								$active_array = array();
								$active_array = explode("/", $schedule_list[$tmp_day][$k]['addinfo6']);
								for($ti=0;$ti<sizeof($time_array);$ti++){
									$active_class = "";
									$add_class = "";

									$account_cnt = intval($account_list[$tmp_day][$schedule_list[$tmp_day][$k]['idx']][$time_array[$ti]]['tot_cnt']);

								//	if($tmp_day.'_'.$schedule_list[$tmp_day][$k]['idx'] == $_GET['items']){
									if(in_array($tmp_day.'_'.$schedule_list[$tmp_day][$k]['idx'], $itemAr)){
										$add_class = "acc_on";
									}

									switch($active_array[$ti]){
										case "Y":
										//	$active_str = "[활성화]";
											if($account_cnt > 0) $active_class = "blue_bold";
											break;
										case "N":
										//	$active_str = "<font style='color:red;'>[비활성화]</font>";
											$active_class = " red_bold";
											break;
									}

									$apply_cont .= '<p class="apply_cont apply_time item'.$tmp_day.'_'.$schedule_list[$tmp_day][$k]['idx'].' '.$add_class.' '.$active_class.'">';
									if($schedule_list[$tmp_day][$k]['addinfo4'] <= $account_cnt){
										$apply_cont .= '<span class="status full">마감</span>';
									} else {
										$apply_cont .= '<span class="status apply">진행</span>';
									}
									$apply_cont .= '&nbsp;&nbsp;<a href="javascript:;" class="time_item '.$active_class.'" data-idx="'.$schedule_list[$tmp_day][$k]['idx'].'" data-tmpday="'.$tmp_day.'" data-time="'.urlencode($time_array[$ti]).'">'.$time_array[$ti].'</a>';
									$apply_cont .= '&nbsp;<span>('.$account_cnt.'/'.$schedule_list[$tmp_day][$k]['addinfo4'].')</span>';
									$apply_cont .= '&nbsp;<span>'.$active_str.'</span>';
									$apply_cont .= '</p>';
								}
							}

							echo $apply_cont;
						}
					}
				}
			}
			?>
		</td>
		<?php
			$day = $tmp_day;
		} else {
			echo "<td bgcolor='#ffffff'></td>";
		}
		
		if ($day != $total_day) {
			if (($day > 0) && ($day < $total_day)) $day++;
		} else {
			$day = "&nbsp;";
		}
	}
	echo "</tr>";
}
?>
</table>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<style>
.modal {max-width:90%;}
.bbs_con {width:100%; border-collapse:separate; border-spacing:0; border-top:2px solid #086bbc;}
.bbs_con .pc_v{}
.bbs_con .mobile_v{display:none}
.bbs_con th {color:#333; font-weight:normal; text-align:center; padding:18px 0; border-bottom:1px solid #ccc; }
.bbs_con td {padding:18px 0; text-align:center; border-bottom:1px solid #e9e9e9}
.bbs_con td img{margin-left:5px}
.bbs_con .mo_line{display:none}
.bbs_con td.left {text-align:left; padding-left:10px;}
.bbs_con td.name a:link, .bbs_con td.name a:visited {color:#333; font-weight:600;}
.blind {font-size:0; line-height:0; width:0px; height:0px;}
.ok, .end {cursor:pointer; }
</style>
<?php
if($detail_items != "" && $times != ""){
	$itemAr = explode("_", $_GET['detail_items']);
	$findDate = date("Y-m-d", strtotime($year."-".$month."-".$itemAr[0]));
?>
<div id="account_list_modal" class="modal">
	<input type="hidden" name="workType" value="acc_complete">
	<input type="hidden" name="sch_idx" value="<?php echo $itemAr[1]; ?>">
	<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style" style="margin-bottom:20px;">
		<tr>
			<td width="15%" class="t_name">출석</td>
			<td width="70%" class="t_value">
				<input type="text" name="aidx" id="aidx" class="input" value=""> <input type="button" value="출석" class="btn_close acc_btn">
				<span style="margin-left:10px;color:#e00000;font-weight:600;" id="acc_result"></span>
				<p style="float:right;"><input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();"></p>
			</td>
		</tr>
	</table>
	<table class="bbs_con" summary="게시물 목록을 보여줍니다.">
		<caption class="blind">게시물 목록</caption>
		<thead class="pc_v">
			<tr>
				<th width="5%" scope="col">연번</th>
				<th width="15%" scope="col">예약자 명</th>
				<th width="5%" scope="col">예약인원</th>
				<th width="" scope="col">연락처</th>
				<th width="15%">예약상태</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sql = " select count(idx) as cnt from wiz_bbs where code = '".$bbs_code."' and addinfo1 = '".$itemAr[1]."' and addinfo2 = '".$findDate."' and addinfo3 = '".$_GET['times']."' order by prino desc ";
			$row = sql_fetch($sql);
			$total = $row['cnt'];

			$sql = " select * from wiz_bbs where code = '".$bbs_code."' and addinfo1 = '".$itemAr[1]."' and addinfo2 = '".$findDate."' and addinfo3 = '".$_GET['times']."' order by prino desc ";
			$result = query($sql);
			for($i=0;$row = sql_fetch_arr($result);$i++){
			?>
			<tr class="pc_v">
				<td><?php echo $total - $i; ?></td>
				<td class="name"><a href="javascript:;" class="account_info" data-idx="<?php echo $row['idx']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo number_format($row['addinfo4']) ?>명</td>
				<td><?php echo $row['hphone']; ?></td>
				<td>
					<?php
					$apply_cont = "";
					switch($row['status']){
						case "1":
							$apply_cont .= '<span class="status apply">접수</span>';
							break;
						case "2":
							$apply_cont .= '<span class="status ok">승인</span>';
							break;
						case "3":
							$apply_cont .= '<span class="status cancel">취소</span>';
							break;
						case "4":
							$apply_cont .= '<span class="status ok" data-idx="'.$row['idx'].'">완료</span>';
							break;
						case "5":
							$apply_cont .= '<span class="status end" data-idx="'.$row['idx'].'">출석</span>';
							break;
					}
					echo $apply_cont;
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<div id="account_info" class="modal" style="display:none;">
	<h3>예약상세정보</h3>
	<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style" style="margin:20px 0px;">
		<tr>
			<td width="15%" class="t_name">예약자명</td>
			<td width="85%" class="t_value" colspan="3" id="acc_name"></td>
		</tr>
		<tr>
			<td width="15%" class="t_name">전화번호</td>
			<td width="35%" class="t_value" id="acc_phone"></td>
			<td width="15%" class="t_name">이메일</td>
			<td width="35%" class="t_value" id="acc_mail"></td>
		</tr>
		<tr>
			<td width="15%" class="t_name">예약정보</td>
			<td width="85%" class="t_value" colspan="3" id="acc_info"></td>
		</tr>
		<tr>
			<td width="15%" class="t_name">지난예약</td>
			<td width="85%" class="t_value" colspan="3">
				<table class="bbs_con" summary="게시물 목록을 보여줍니다.">
					<caption class="blind">게시물 목록</caption>
					<thead class="pc_v">
						<tr>
							<th width="10%" scope="col">상태</th>
							<th width="20%" scope="col">예약일시</th>
							<th width="" scope="col">프로그램명</th>
						</tr>
					</thead>
					<tbody id="already_acc">
					</tbody>
				</table>
			</td>
		</tr>
	</table>
</div>
<script>
$("#account_list_modal").modal();
$("#aidx").focus();
</script>
<?php } ?>
<script>
function changeUrl(title, url){
	if(typeof(history.pushState) != "undefined"){
		var obj = { Title: title, ChangeUrl: url };
		history.pushState(obj, obj.Title, obj.ChangeUrl);
	}
}

function excelDown(){

	var url = "application_excel.php?code=<?php echo $code; ?>&items=<?php echo $_GET['detail_items']; ?>&times=<?php echo ($_GET['times'] ?? '') . urlencode($_GET['times'] ?? ''); ?>&year=<?php echo $year; ?>&month=<?php echo $month; ?>";
	location.href = url;
}

$(document).on("click", ".account_info", function(){
	var idx = $.trim($(this).attr("data-idx"));

	if(idx != ""){
		jQuery.ajax({
			url: "/twcenter/manage/schedule/ajax_acc.php",
			type : "POST",
			data: "workType=acc_info&sch_idx=<?php echo $itemAr[1]; ?>&idx="+idx+"&code=<?php echo $code; ?>",
			dataType: "json",
			error: function(xhr,textStatus,errorThrown){
			},
			beforeSend: function() {
			},
			success: function(data){
				console.log(data);
				if(data.result == "success"){
					var acc_info = "";
						acc_info += "프로그램명 : " + data.datas.acc_subject + "<br>";
						acc_info += "일시 : " + data.datas.addinfo2 + " " + data.datas.addinfo3 + "<br>";
						acc_info += "인원 : " + data.datas.addinfo4 + "<br>";
					
					var already_list = "";

					if(data.datas.already_list != null){
						for(var i=0;i<data.datas.already_list.length;i++){
							switch(data.datas.already_list[i].status){
								case "3":
									var status_str = "취소";
									break;
								case "4":
									var status_str = "완료";
									break;
								case "5":
									var status_str = "출석";
									break;
							}
							already_list +=		"<tr class='pc_v'>\r\n";
							already_list +=			"<td>"+status_str+"</td>\r\n";
							already_list +=			"<td>"+data.datas.already_list[i].addinfo2+" "+data.datas.already_list[i].addinfo3+"</td>\r\n";
							already_list +=			"<td>"+data.datas.already_list[i].acc_subject+"</td>\r\n";
							already_list +=		"</tr>\r\n";
						}
					}

					$("#acc_name").html(data.datas.name);
					$("#acc_phone").html(data.datas.hphone);
					$("#acc_mail").html(data.datas.email);

					$("#acc_info").html(acc_info);
					$("#already_acc").html(already_list);
				}
			},
			complete: function(){
				$("#account_info").modal({closeExisting: false});
			}
		});
	}
});

$(document).on("click", ".end", function(){
	var aidx = $(this).attr("data-idx");

	if(aidx != ""){
		jQuery.ajax({
			url: "/twcenter/manage/schedule/ajax_acc.php",
			type : "POST",
			data: "workType=acc_ok&sch_idx=<?php echo $itemAr[1]; ?>&aidx="+aidx,
			error: function(xhr,textStatus,errorThrown){
			},
			beforeSend: function() {
			},
			success: function(data){
				console.log(data);
				switch(data){
					case "success":
						location.reload();
						break;
					default:
						$("#acc_result").text("완료처리에 실패하였습니다.");
				}
			},
			complete: function(){
			}
		});
	}
});

$(document).on("click", ".ok", function(){
	$("#aidx").val($(this).attr("data-idx"));
	$(".acc_btn").trigger("click");
});

$(document).on("keydown", "#aidx", function(e){
	if(e.keyCode == 13){
		$(".acc_btn").trigger("click");
	}
});

$(document).on("click", ".acc_btn", function(){
	var aidx = $.trim($("#aidx").val());

	if(aidx != ""){
		jQuery.ajax({
			url: "/twcenter/manage/schedule/ajax_acc.php",
			type : "POST",
			data: "workType=acc_complete&sch_idx=<?php echo $itemAr[1]; ?>&aidx="+aidx,
			error: function(xhr,textStatus,errorThrown){
			},
			beforeSend: function() {
			},
			success: function(data){
				console.log(data);
				switch(data){
					case "success":
						location.reload();
						break;
					default:
						$("#acc_result").text("출석체크에 실패하였습니다. 화면을 새로고침 하거나 수동으로 출석체크 바랍니다.");
				}
			},
			complete: function(){
			}
		});
	}
});

$(document).on("click", ".schedule_item", function(){
	if($(this).hasClass("on")){
		$(this).removeClass("on");
	} else {
		$(this).addClass("on");
	}

	var items = "";

	$(".on").each(function(){
		var idx = $(this).attr("data-idx");
		var tmpday = $(this).attr("data-tmpday");
		if(items != "") items += "|";
		items += tmpday+"_"+idx;
	});

//	$(".apply_time").hide();

//	$(".item"+tmpday+"_"+idx).show();

	location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $_GET['code']; ?>&year=<?php echo $_GET['year']; ?>&month=<?php echo $_GET['month']; ?>&items="+items;
});

$(document).on("click", ".time_item", function(){
	var idx = $(this).attr("data-idx");
	var tmpday = $(this).attr("data-tmpday");
	var times = $(this).attr("data-time");

	location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $_GET['code']; ?>&year=<?php echo $_GET['year']; ?>&month=<?php echo $_GET['month']; ?>&items=<?php echo $_GET['items']; ?>&detail_items="+tmpday+"_"+idx+"&times="+times;
});

$("#account_list_modal").on($.modal.CLOSE, function(event, modal){
	changeUrl('test',"<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $_GET['code']; ?>&year=<?php echo $_GET['year']; ?>&month=<?php echo $_GET['month']; ?>&items=<?php echo $_GET['items']; ?>");
});

function allchange(val){
	console.log(val);
	switch(val){
		case "open":
			$(".schedule_item").addClass("on");

			var items = "";

			$(".on").each(function(){
				var idx = $(this).attr("data-idx");
				var tmpday = $(this).attr("data-tmpday");
				if(items != "") items += "|";
				items += tmpday+"_"+idx;
			});

		//	$(".apply_time").hide();

		//	$(".item"+tmpday+"_"+idx).show();

			location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $_GET['code']; ?>&year=<?php echo $_GET['year']; ?>&month=<?php echo $_GET['month']; ?>&items="+items;
			break;
		case "close":
			location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $_GET['code']; ?>&year=<?php echo $_GET['year']; ?>&month=<?php echo $_GET['month']; ?>";
			break;
	}
}
</script>
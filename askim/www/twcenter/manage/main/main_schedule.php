<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/manage/head.php'; ?>

<link rel="stylesheet" type="text/css" href="/twcenter/js/dist/jquery.jqplot.min.css" />
<script type="text/javascript" src="/twcenter/js/dist/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/excanvas.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="/twcenter/js/dist/plugins/jqplot.pointLabels.min.js"></script>
<script type="text/javascript">
function memoIn_Proc() {

	var mode = "memoIn";
	var id_manamemo = $("#id_manamemo").val();

	$.ajax({
		url: '../config/memo_process.php',
		type: 'POST',
		data: {"mode":mode, "manamemo":id_manamemo},
		timeout: 10000,
		success: function(data) {
			if(data == "OK"){
				alert("관리메모가 등록/수정되었습니다.");
				$(".memouptime").load("../config/memo_refresh.php #result");
			}
		},
		error: function(data) {
			console.log(data);
		}
	});
	
}

function memoDe_Proc() {

	var mode = "memoDe";

	$.ajax({
		url: '../config/memo_process.php',
		type: 'POST',
		data: {"mode":mode},
		timeout: 10000,
		success: function(data) {
			if(data == "OK"){
				alert("관리메모가 삭제되었습니다.");
				$("#id_manamemo").val('');
				$(".memouptime").load("../config/memo_refresh.php #result");
			}
		},
		error: function(data) {
			console.log(data);
		}
	});
	
}

</script>
<?
// 하드사용량
/*
$disk_use = exec("du -h ../../../");
$disk_use = str_replace(array("M","/","..","	"),"",$disk_use);
$disk_graph = round($disk_use*2)/10;
*/

// 디비사용량
/*
$sql = "show table status like '%'";
$result = query($sql) or error("sql error");
while($sys_db = sql_fetch_obj($result)){
$db_use += $sys_db->Data_length;
}
$db_use = ceil($db_use/(8*102400));
$db_graph = round($db_use*2)/10;
*/

// 총게시판수
$sql = "select count(code) as cnt from wiz_bbsinfo";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);
$bbs_num = $row['cnt'];

// 총게시물수
$sql = "select count(idx) as cnt from wiz_bbs";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);
$bbs_total = $row['cnt'];

// 오늘게시물수
$today = date("Ymd");
$sql = "select count(idx) as cnt from wiz_bbs where DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d') = '".$today."'";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);
$bbs_today = $row['cnt'];

// 오늘댓글
$sql = "select count(idx) as cnt from wiz_comment where DATE_FORMAT(wdate, '%Y%m%d') = '".$today."'";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);
$comment_today = $row['cnt'];

// 방문자
$sql = "select time, sum(cnt) as cnt from wiz_contime group by substring(time,1,8) order by substring(time,1,8) desc";
$result = query($sql) or error("sql error");
$visit_cnt = sql_fetch_obj($result);
$today_cnt = $visit_cnt->cnt;
if($today_cnt == "") $today_cnt = "0";

// 어제방문자
$to_date  = date("Y-m-d");
$yes_date  = date("Y-m-d", strtotime("-1 day", strtotime($to_date)));
$prev_period   = str_replace("-","",$yes_date."00");
$next_period   = str_replace("-","",$yes_date."24");
$period_sql = " where time >= '$prev_period' and time <= '$next_period' ";


$sql = "select time, sum(cnt) as cnt from wiz_contime $period_sql";
$result = query($sql) or error("sql error");
$visit_cnt = sql_fetch_obj($result);
$yester_cnt = $visit_cnt->cnt;
if($visit_cnt == "") $visit_cnt = "0";


$sql = "select sum(cnt) as cnt from wiz_contime";
$result = query($sql) or error("sql error");
$visit_cnt = sql_fetch_obj($result);
$total_cnt = $visit_cnt->cnt;
if($total_cnt == "") $total_cnt = "0";

// 현재접속자
$dir = @opendir(WIZHOME_PATH."/data/connect");
$now_time = mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'));

while(($file = readdir($dir)) !== false)
{
	if(($file != ".") && ($file != ".."))
	{
		$now_cnt++;
		$fileinfo = stat(WIZHOME_PATH."/data/connect/$file");
		//print_r($fileinfo);
		if($fileinfo[9] < $now_time - 120)
		{
			unlink(WIZHOME_PATH."/data/connect/$file");
			$now_cnt--;
		}
	}
}
if($now_cnt == "") $now_cnt = "0";

$time_stamp = time();
$s_time = "";
$e_time = "23:59:59";
$this_date  = date("Y-m-d");

$date1    = date("Y-m-d",strtotime("-6 days", $time_stamp));
$date2    = date("Y-m-d");
$new_date = date("Y-m-d", strtotime("-1 day", strtotime($date1)));
$i=1;
while(true) {

	$new_date  = date("Y-m-d", strtotime("+1 day", strtotime($new_date)));
	$this_date = $new_date;

	$substring_sql = "substring(time,5,2)";
	$prev_period   = str_replace("-","",$this_date."00");
	$next_period   = str_replace("-","",$this_date."24");

	$period_sql = " where time >= '$prev_period' and time <= '$next_period' ";

	## 쓰리웨이에서 제공하는 기본 접속현황
	${"con_sql_".$i} = "
	
		select 
		
			sum(cnt) as total_mem_con
			
		from 
			wiz_contime 
			
			$period_sql
			
	";

	${"con_result_".$i} = query(${"con_sql_".$i}) or error("sql error");
	${"con_row_".$i} = sql_fetch_arr(${"con_result_".$i});
	${"total_mem_con_".$i} = ${"con_row_".$i}[total_mem_con];
	if(empty(${"con_row_".$i}[total_mem_con])) ${"total_mem_con_".$i} = 0;

	$total_mem_con     .= ${"total_mem_con_".$i}.",";

	## 주문접수
	${"sql_".$i} = "
	
		select 
		
			sum(total_price) as total_price_OR 
			
		from 
		
			wiz_order 
			
		where 
		
			order_date between '$this_date' and '$this_date $e_time' and status='OR'
			
	";
	${"result_".$i} = query(${"sql_".$i});
	${"row_".$i} = sql_fetch_arr(${"result_".$i});
	${"total_price_OR_".$i} = ${"row_".$i}[total_price_OR];
	if(empty(${"row_".$i}[total_price_OR])) ${"total_price_OR_".$i} = 0;

	## 주문취소
	${"sql2_".$i} = "
	
		select 
		
			sum(total_price) as total_price_OC 
			
		from 
		
			wiz_order 
			
		where 
		
			cancel_date between '$this_date' and '$this_date $e_time' and status='OC'
			
	";
	${"result2_".$i} = query(${"sql2_".$i});
	${"row2_".$i} = sql_fetch_arr(${"result2_".$i});
	${"total_price_OC_".$i} = ${"row2_".$i}[total_price_OC];
	if(empty(${"row2_".$i}[total_price_OC])) ${"total_price_OC_".$i} = 0;

	## 결제/배송

	${"sql3_".$i} = "
	
		select 
		
			sum(total_price) as total_price_OY 
			
		from 
		
			wiz_order 
			
		where 
		
			pay_date between '$this_date' and '$this_date $e_time' and (status='OY' or status='DR' or status='DI' or status='DC')
			
	";
	${"result3_".$i} = query(${"sql3_".$i});
	${"row3_".$i} = sql_fetch_arr(${"result3_".$i});
	${"total_price_OY_".$i} = ${"row3_".$i}[total_price_OY];
	if(empty(${"row3_".$i}[total_price_OY])) ${"total_price_OY_".$i} = 0;

	$total_price_OR     .= ${"total_price_OR_".$i}.",";
	$total_price_OC     .= ${"total_price_OC_".$i}.",";
	$total_price_OY     .= ${"total_price_OY_".$i}.",";

	if($new_date == $date2) break;
	$i++;
}
$total_price_OR = (substr($total_price_OR, -1) == ',') ? substr_replace($total_price_OR, '', -1) : $total_price_OR;
$total_price_OC = (substr($total_price_OC, -1) == ',') ? substr_replace($total_price_OC, '', -1) : $total_price_OC;
$total_price_OY = (substr($total_price_OY, -1) == ',') ? substr_replace($total_price_OY, '', -1) : $total_price_OY;
$total_mem_con  = (substr($total_mem_con, -1) == ',')  ? substr_replace($total_mem_con, '', -1) : $total_mem_con;

$prev_date1 = date("m-d");
$prev_date2 = date("m-d",strtotime("-1 days", $time_stamp));
$prev_date3 = date("m-d",strtotime("-2 days", $time_stamp));
$prev_date4 = date("m-d",strtotime("-3 days", $time_stamp));
$prev_date5 = date("m-d",strtotime("-4 days", $time_stamp));
$prev_date6 = date("m-d",strtotime("-5 days", $time_stamp));
$prev_date7 = date("m-d",strtotime("-6 days", $time_stamp));

$week  = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
$yoil1 = $week[date("w", strtotime(date("Y")."-".$prev_date1))];
$yoil2 = $week[date("w", strtotime(date("Y")."-".$prev_date2))];
$yoil3 = $week[date("w", strtotime(date("Y")."-".$prev_date3))];
$yoil4 = $week[date("w", strtotime(date("Y")."-".$prev_date4))];
$yoil5 = $week[date("w", strtotime(date("Y")."-".$prev_date5))];
$yoil6 = $week[date("w", strtotime(date("Y")."-".$prev_date6))];
$yoil7 = $week[date("w", strtotime(date("Y")."-".$prev_date7))];

/** 메뉴구성 **/
$menu_tmp = explode("/",$site_info['menu_use']);
for($ii=0; $ii<count($menu_tmp); $ii++){
	$menu_arr[$menu_tmp[$ii]] = true;
}

?>
<script type="text/javascript">
<? if($menu_arr["PRODUCT"]==true){ ?>
$(function() {
	var OR = [<?=$total_price_OR?>];
	var OY = [<?=$total_price_OY?>];
	var OC = [<?=$total_price_OC?>];
	var ticks = ['<?=$prev_date7?> <?=$yoil7?>', '<?=$prev_date6?> <?=$yoil6?>', '<?=$prev_date5?> <?=$yoil5?>', '<?=$prev_date4?> <?=$yoil4?>', '<?=$prev_date3?> <?=$yoil3?>', '<?=$prev_date2?> <?=$yoil2?>', '<?=$prev_date1?> <?=$yoil1?>'];

	$.jqplot("graph",[OR,OY,OC],{
		animate: !$.jqplot.use_excanvas,
		seriesDefaults: {
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: false },
			rendererOptions:{ 
				highlightMouseOver:true,
				smooth:true
			}
		},
		axesDefaults: {
			tickRenderer: $.jqplot.CanvasAxisTickRenderer,
			pad : 1.2,
			tickOptions: {
				fontFamily: 'Tahoma',
				textColor: '#000000'
			}
		},
		series:[{renderer:$.jqplot.BarRenderer, label:'주문접수'}, {renderer:$.jqplot.BarRenderer, label:'결제/배송'}, {renderer:$.jqplot.BarRenderer, label:'주문취소'}],
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks,
				tickOptions: {
					fontSize: "8pt"
				}
			},
			yaxis: {
				pad: 0,
				tickOptions: {
					formatString: "%'d원",
					fontSize: "8pt",
					show: true
				}
			}
		},
		highlighter : {
			show: true,
			sizeAdjust: 6,
			formatString: "<a %s /> %s"
		},
		legend:{
			show:true,
			location: 'nw',
			xoffset: 0,
			yoffset: 0
		},
		grid:{
			show:true,
			background:'#ffffff'
		}

	});
	
});
<? } ?>
$(function() {
	var CON = [<?=$total_mem_con?>];
	var ticks = ['<?=$prev_date7?> <?=$yoil7?>', '<?=$prev_date6?> <?=$yoil6?>', '<?=$prev_date5?> <?=$yoil5?>', '<?=$prev_date4?> <?=$yoil4?>', '<?=$prev_date3?> <?=$yoil3?>', '<?=$prev_date2?> <?=$yoil2?>', '<?=$prev_date1?> <?=$yoil1?>'];

	$.jqplot("graph2",[CON],{
		animate: !$.jqplot.use_excanvas,
		seriesDefaults: {
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true },
			rendererOptions:{ 
				highlightMouseOver:false,
				varyBarColor: true
			}
		},
		/*seriesColors: ['steelblue'],*/
		axesDefaults: {
			tickRenderer: $.jqplot.CanvasAxisTickRenderer,
			pad : 1.2,
			tickOptions: {
				fontFamily: 'Tahoma',
				textColor: '#000000'
			}
		},
		legend:{show:false},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks,
				tickOptions: {
					fontSize: "8pt"
				}
			},
			yaxis: {
				min: 0,
				tickOptions:{
					formatString:"%'d명",
					fontSize: "8pt",
					show: true
				}
			}
		},
		highlighter : {
			show: false,
			sizeAdjust: 6,
			formatString: "<a %s /> %s"
		},
		grid:{
			show:true,
			background:'#ffffff'
		}

	});
	
});
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td height="3"></td></tr>
	<? if($menu_arr["PRODUCT"]==true){ ?>
	<tr>
		<td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					    <h3 class="main_tit">최근매출통계 <span class="more"><a href="../product/order_list.php">+ More</a></span></h3>
					</td>
				</tr>
				<tr>
					<td height="14"></td>
				</tr>
				<tr>
					<td>
						<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
							<tr>
								<td align="center" height="230">
									<div id="graph" style="margin-left:7px; width: 99%; height: 225px; margin: auto;"></div>
								</td>
							</tr>
						</table>

					</td>
				</tr> 
			</table>
		</td>
		<td width="20"></td>
		<td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<h3 class="main_tit">최근매출집계 <span class="more"><a href="../product/order_list.php">+ More</a></span></h3>
					</td>
				</tr>
				<tr>
					<td height="14"></td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
							<tr>
								<td rowspan='2' class="t_value_main"></td>
								<td colspan='2' class="t_value_main_ord">주문접수</td>
								<td colspan='2' class="t_value_main_pay">결제/배송</td>
								<td colspan='2' class="t_value_main_can">주문취소</td>
							</tr>
							<tr>
								<td class="t_value_main_ord">금액</td>
								<td class="t_value_main_ord">건수</td>
								<td class="t_value_main_pay">금액</td>
								<td class="t_value_main_pay">건수</td>
								<td class="t_value_main_can">금액</td>
								<td class="t_value_main_can">건수</td>
							</tr>
							<?
							$time_stamp = time();
							$this_date  = date("Y-m-d");

							$date1    = date("Y-m-d",strtotime("-6 days", $time_stamp));
							$date2    = date("Y-m-d");
							$new_date = date("Y-m-d", strtotime("-1 day", strtotime($date1)));

							$i=7;
							$j=1;
							while(true){
							
								$new_date  = date("Y-m-d", strtotime("+1 day", strtotime($new_date)));
								$this_date = $new_date;

								## 주문접수
								${"sql_".$j} = "
								
									select 
									
										count(orderid) as total_order_OR,
										sum(total_price) as total_price_OR
										
									from 
									
										wiz_order 
										
									where 
									
										order_date between '$this_date' and '$this_date $e_time' and status='OR'
										
								";
								//echo "<pre>".${"sql_".$j}."</pre>";
								${"result_".$j} = query(${"sql_".$j});
								${"row_".$j} = sql_fetch_arr(${"result_".$j});

								${"total_price_OR".$j} = ${"row_".$j}[total_price_OR];
								${"total_order_OR".$j} = ${"row_".$j}[total_order_OR];
								if(empty(${"total_price_OR".$j})) ${"total_price_OR".$j} = 0;
								if(empty(${"total_order_OR".$j})) ${"total_order_OR".$j} = 0;

								## 주문취소
								${"sql2_".$j} = "
								
									select 
									
										sum(total_price) as total_price_OC,
										count(orderid) as total_order_OC
										
									from 
									
										wiz_order 
										
									where 
									
										cancel_date between '$this_date' and '$this_date $e_time' and status='OC'
										
								";
								${"result2_".$j} = query(${"sql2_".$j});
								${"row2_".$j} = sql_fetch_arr(${"result2_".$j});

								${"total_price_OC".$j} = ${"row2_".$j}[total_price_OC];
								${"total_order_OC".$j} = ${"row2_".$j}[total_order_OC];

								if(empty(${"total_price_OC".$j})) ${"total_price_OC".$j} = 0;
								if(empty(${"total_order_OC".$j})) ${"total_order_OC".$j} = 0;

								## 결제/배송관련
								${"sql3_".$j} = "
								
									select 
									
										sum(total_price) as total_price_OY,
										count(orderid) as total_order_OY
										
									from 
									
										wiz_order 
										
									where 
									
										pay_date between '$this_date' and '$this_date $e_time' and (status='OY' or status='DR' or status='DI' or status='DC')
										
								";

								${"result3_".$j} = query(${"sql3_".$j});
								${"row3_".$j} = sql_fetch_arr(${"result3_".$j});

								${"total_price_OY".$j} = ${"row3_".$j}[total_price_OY];
								${"total_order_OY".$j} = ${"row3_".$j}[total_order_OY];

								if(empty(${"total_price_OY".$j})) ${"total_price_OY".$j} = 0;
								if(empty(${"total_order_OY".$j})) ${"total_order_OY".$j} = 0;

								$prev_date  = date("m-d");
								$prev_date2 = date("m-d",strtotime("-1 days", $time_stamp));
								$prev_date3 = date("m-d",strtotime("-2 days", $time_stamp));
								$prev_date4 = date("m-d",strtotime("-3 days", $time_stamp));
								$prev_date5 = date("m-d",strtotime("-4 days", $time_stamp));
								$prev_date6 = date("m-d",strtotime("-5 days", $time_stamp));
								$prev_date7 = date("m-d",strtotime("-6 days", $time_stamp));

							?>
							<tr>
								<td class="t_value_main2" width="16%"><?=${"prev_date".$i}?></td>
								<td class="t_value_main_L" width="14%"><?=number_format(${"total_price_OR".$j})?>원</td>
								<td class="t_value_main_L" width="14%"><?=number_format(${"total_order_OR".$j})?></td>
								<td class="t_value_main_L2" width="14%"><?=number_format(${"total_price_OY".$j})?>원</td>
								<td class="t_value_main_L2" width="14%"><?=number_format(${"total_order_OY".$j})?></td>
								<td class="t_value_main_L" width="14%"><?=number_format(${"total_price_OC".$j})?>원</td>
								<td class="t_value_main_L" width="14%"><?=number_format(${"total_order_OC".$j})?></td>
							</tr>
							<?

								if($new_date == $date2) break;
								$i--;
								$j++;
							}
							?>
						</table>
					</td>
				</tr>

				<!-- <tr>
					<td>
						<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
							<tr>
								<td class="t_name_main" width="28%">주문번호</td>
								<td class="t_name_main" width="18%">주문자명</td>
								<td class="t_name_main" width="18%">결제수단</td>
								<td class="t_name_main" width="18%">금액</td>
								<td class="t_name_main" width="18%">처리상태</td>
							</tr>
							<?
							$sql = "
							
								select 
								
									order_date       ,
									orderid          ,
									send_name        ,
									pay_method       ,
									total_price      ,
									send_id          ,
									status
									
								from
								
									wiz_order wo
									
								where
								
									orderid !='' and
									status !=''
									order by order_date desc limit 0,6
									
							";
							$result = query($sql);
							while($row = sql_fetch_obj($result)){

								if($row->send_id == "") $send_name = $row->send_name." <span class='tit_alt'>[비회원]</span>";
								else $send_name = "<a href='../member/member_input.php?mode=update&id=$row->send_id'>$row->send_name <span class='tit_alt'>[$row->send_id]</span></a>";

								$orderid = "<a href='../product/order_info.php?orderid=$row->orderid'>".$row->orderid."</a>";
							?>
							<tr>
								<td class="t_value_main"><?=$orderid?></td>
								<td class="t_value_main"><?=$send_name?></td>
								<td class="t_value_main"><?=pay_method($row->pay_method)?></td>
								<td align='right' class="t_value_main" ><?=number_format($row->total_price)?>원&nbsp;&nbsp;</td>
								<td class="t_value_main"><?=order_status($row->status)?></td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr> -->

			</table>
		</td>
	</tr>
	<tr>
		<td height="30"></td>
		<td></td>
		<td></td>
	</tr>
	<? } ?>

	<tr>
		<td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					     <h3 class="main_tit">최근게시물 <span class="more"><a href="../bbs/bbs_list.php">+ More</a></span></h3>
					</td>
				</tr>
				<tr>
					<td height="14"></td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?
						$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));

						$sql = "
						
						select 
						
							wb.idx                                                        ,
							wb.code                                                       ,
							wb.subject                                                    ,
							date_format(from_unixtime(wb.wdate), '%Y-%m-%d') as wdate     ,
							wi.title                                                      ,
							wi.type
							
						from
						
							wiz_bbs wb,
							wiz_bbsinfo wi
							
						where
						
							wb.code = wi.code order by wb.idx desc limit 8
							
						";
						$result = query($sql) or error("sql error");
						$total = sql_fetch_row($result);
						while($row = sql_fetch_arr($result)){
							$new = "";
							$wtime = mktime(0,0,0,substr($row['wdate'],5,2),substr($row['wdate'],8,2),substr($row['wdate'],0,4));
							if(($ttime-$wtime)/86400 <= 2) $new = "<img src='../image/new.gif' border='0' align='absmiddle'>";	// new
							$row['wdate'] = str_replace("-","/",$row['wdate']);

							if(!strcmp($row['type'], "SCH")) $purl = "../schedule/list.php";
							else $purl = "../bbs/list.php";
						?>
							<tr>
								<td height="28" align="center" width="12"><img src="../image/left_s_arrow.gif" /></td>
								<td><a href="<?=$purl?>?code=<?=$row['code']?>">[<?=$row['title']?>] <?=$row['subject']?></a> <?=$new?></td>
								<td align="right">[<?=$row['wdate']?>]</td>
							</tr>
							<tr>
								<td colspan="3" height="1" background="../image/dot_bg.gif"></td>
							</tr>
						<?
						}
						?>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="20"></td>
		<td width="49%" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					    <h3 class="main_tit">접속자현황 <span class="more"><a href="../connect/connect_list.php">+ More</a></span></h3>
					</td>
				</tr>
				<tr>
					<td height="14"></td>
				</tr>
				<tr>
					<td>
						<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
							<tr>
								<td align="center" height="230">
									<div id="graph2" style="margin-left:7px; width: 99%; height: 220px; margin: auto;"></div>
								</td>
							</tr>
						</table>

					</td>
				</tr> 

			</table>
		</td>
	</tr>
	<tr>
		<td height="30"></td>
		<td></td>
		<td></td>
	</tr>

	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td style="border:4px solid #f0f0f0; padding:15px">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="120" valign="top">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td class="tit_sub">게시판현황</td>
										</tr>
										<tr>
											<td valign="top">
												<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
													<tr>
														<td width="20%" class="t_name">총게시판수</td>
														<td width="30%" align="right"><span class="num_size"><?=$bbs_num?></span> 개&nbsp;</td>
														<td width="20%" class="t_name">총게시물</td>
														<td width="30%" align="right"><span class="num_size"><?=$bbs_total?></span> 개&nbsp;</td>
													</tr>
													<tr>
														<td height="29" class="t_name">오늘게시물</td>
														<td align="right"><span class="num_size"><?=$bbs_today?></span> 개&nbsp;</td>
														<td width="20%" class="t_name">오늘댓글</td>
														<td width="30%" align="right"><span class="num_size"><?=$comment_today?></span> 개&nbsp;</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;</td>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td style="border:4px solid #f0f0f0; padding:15px">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="120" valign="top" bgcolor="#FFFFFF">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td class="tit_sub">접속관련정보</td>
										</tr>
										<tr>
											<td valign="top">
												<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
													<tr>
														<td width="20%" class="t_name">현재접속자</td>
														<td width="30%" align="right"><span class="num_size2"><?=number_format($now_cnt)?></span> 명&nbsp;</td>
														<td width="20%" class="t_name">오늘접속자</td>
														<td width="30%" align="right"><span class="num_size2"><?=number_format($today_cnt)?></span> 명&nbsp;</td>
													</tr>
													<tr>
														<td height="29" class="t_name">총접속자</td>
														<td align="right"><span class="num_size2"><?=number_format($total_cnt)?></span> 명&nbsp;</td>
														<td width="20%" class="t_name">어제접속자</td>
														<td width="30%" align="right"><span class="num_size2"><?=number_format($yester_cnt)?></span> 명&nbsp;</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td height="23"></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="border:4px solid #f0f0f0; padding:15px">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="tit_sub">회원정보</td>
				</tr>
				<tr>
					<td>
						<?
						// 전체회원수
						$sql = "select idx from wiz_member";
						$result = query($sql);
						$all_total = sql_fetch_row($result);
						
						// 오늘가입자수
						$sql = "select idx from wiz_member where wdate between '".date('Y-m-d')." 00:00:00' and '".date('Y-m-d')." 23:59:59'";
						$result = query($sql);
						$today_total = sql_fetch_row($result);
						
						// 오늘탈퇴회원수
						$sql = "select idx from wiz_bbs where code = '[memout]' and FROM_UNIXTIME(wdate, '%Y-%m-%d') = '".date('Y-m-d')."'";
						$result = query($sql);
						$today_out = sql_fetch_row($result);
						?>
						<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
							<tr>
								<td height="25" align="center" class="t_name">오늘가입회원</td>
								<td align="center" class="t_name">오늘탈퇴회원</td>
								<td align="center" class="t_name">전체회원수</td>
							</tr>
							<tr bgcolor="#ffffff">
								<td height="35" align="center" ><span class="num_size3"><?=number_format($today_total)?></span> 명</td>
								<td align="center" class="t_value"><span class="num_size3"><?=number_format($today_out)?></span> 명</td>
								<td align="center" class="t_value"><span class="num_size3"><?=number_format($all_total)?></span> 명</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;</td>
		<td style="border:4px solid #f0f0f0; padding:15px">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="tit_sub" colspan="2">사이트 관리메모</td>
				</tr>
				<tr>
					<td>
						<textarea name="manamemo" id="id_manamemo" rows="5" cols="85" class="textarea"><?=$site_info['manamemo']?></textarea>
					</td>
					<td>
						최종수정일 : <span class="memouptime"><?=$site_info['memoupdate']?></span><BR><BR>
						<input type="button" value='메모저장' class="mana_memo_ac" onclick="memoIn_Proc()" style="cursor:pointer">&nbsp;
						<input type="button" value='전체삭제' class="mana_memo_de" onclick="memoDe_Proc()" style="cursor:pointer">
					</td>

				</tr>
			</table>
		</td>
		<td width="25"></td>
	</tr>

	<tr>
		<td height="23"></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="border:4px solid #f0f0f0; padding:15px">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="tit_sub"><a id="mainReload" name="mainReload">일정관리</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="1" class="t_style">
							<tr>
								<td>
									<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/module/schedule_s_n.php'; ?>
								</td>
							</tr>
						</table>
					</td>
					<td>&nbsp;</td>
					<td></td>
					<td width="25"></td>
				</tr>
			</table>
<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/manage/foot.php'; ?>
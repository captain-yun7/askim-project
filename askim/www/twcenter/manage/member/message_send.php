<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$param = "sdate=".$sdate."&edate=".$edate."&level=".$level."&searchopt=".$searchopt."&searchkey=".$searchkey."&reemail=".$reemail."&".$menucodeParam;
?>
<? include "../head.php"; ?>
<? include "../../lib/datepicker_lib.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

	if(frm.subject.value == ""){
		alert("제목을 입력하세요");
		frm.subject.focus();
		return false;
	}
	try{ content.outputBodyHTML(); } catch(e){ }
	if(frm.content.value == ""){
		alert("내용을 입력하세요.");
		return false;
	}

}

$(function() {

	$('#sdate').datepicker({
		language: 'kr',
		autoClose: true

	});
	$('#edate').datepicker({
		language: 'kr',
		autoClose: true
	});

});

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">쪽지발송</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">회원을 검색하여 단체 쪽지를 발송합니다.</td>
        </tr>
      </table>

      <br>
      <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="detailsearch" value="<?=$detailsearch?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="ffffff">
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
            <tr>
            <td width="15%" class="t_name">&nbsp; 조건검색</td>
            <td width="85%" class="t_value">

             <table cellspacing="2" cellpadding="0">
             <tr>
             <td>
               <select name="level" class="select">
               <option value=""> :: 등급선택 ::</option>
               <?=level_list();?>
               </select>
             </td>
             <td>
               <select name="searchopt" class="select">
               <option value="name">고객명
               <option value="id">아이디
               <option value="email">이메일
               <option value="tphone">전화번호
               <option value="hphone">휴대폰
               </select>
             </td>
             <td><input type="text" name="searchkey" value="<?=$searchkey?>" class="input"></td>
             <!-- <td><input type="image" src="../image/btn_search.gif"></td> -->
             </tr>
             </table>
             <script language="javascript">
             <!--
             level = document.searchForm.level;
             for(ii=0; ii<level.length; ii++){
               if(level.options[ii].value == "<?=$level?>")
                 level.options[ii].selected = true;
             }
             searchopt = document.searchForm.searchopt;
             for(ii=0; ii<searchopt.length; ii++){
               if(searchopt.options[ii].value == "<?=$searchopt?>")
                 searchopt.options[ii].selected = true;
             }
             -->
             </script>

           </td>
           </tr>
           <tr>
            <td width="120" class="t_name">&nbsp; 가입기간</td>
            <td class="t_value">
			<?
			//$default_day  = date('Y-m-d', strtotime(date('Y-m-d'))-(3600*24*60));

			//if(!empty($sdate)) $sdate   = $sdate; else $sdate = $default_day;
			//if(!empty($edate)) $edate   = $edate; else $edate = date("Y-m-d");
			?>
              <span class="calendar">
				  <input type="text" name="sdate" id="sdate" value="<?=$sdate?>" size="15" class="datepicker-here input2 input120"> ~
				  <input type="text" name="edate" id="edate" value="<?=$edate?>" size="15" class="datepicker-here input2 input120">
			  </span>
            </td>
            </tr>
           </table>
          </td>
        </tr>
      </table>
	  <br>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center">
					<input type="submit" value="검색" class="search_btn2">&nbsp;
					<input type="button" value="전체회원" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
				</td>
			</tr>
		</table>
	  </form>

      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="15"></td></tr>
      </table>
      <?
      	$sql = "select id from wiz_member";
      	$result = query($sql) or error("sql error");
      	$all_total = sql_fetch_row($result);


         $today = date('n-d');
         $toyear = date('Y');

         $age_syear = substr($toyear-($age+9),-2)+1;
         $age_eyear = substr($toyear-$age,-2)+2;

         $join_sdate = $prev_year."-".$prev_month."-".$prev_day;
         $join_edate = $next_year."-".$next_month."-".$next_day;


         $sql = "select id,passwd,name,hphone,email,visit,reemail,wdate from wiz_member where id != '' ";

         if($sdate != "") 		$sql .= " and wdate > '$sdate'";
         if($edate != "") 		$sql .= " and wdate <= '$edate 23:59:59'";
         if($searchkey != "")	$sql .= " and $searchopt like '%$searchkey%'";
         if($level != "") 		$sql .= " and level = '$level'";
         if($birthday == "Y")	$sql .= " and birthday like '%$today'";
         if($memorial == "Y")	$sql .= " and memorial like '%$today'";
         if($age != "")			$sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
         if($address != "")		$sql .= " and address like '%$address%'";
         if($job != "")			$sql .= " and job = '$job'";
         if($marriage != "")	$sql .= " and marriage = '$marriage'";
         if($reemail == "N")	$sql .= " and reemail != 'N'";

         $sql .=" order by wdate desc";
		 //echo $sql;
         $result = query($sql) or error("sql error");
         $total = sql_fetch_row($result);

         $rows = 6;
         $lists = 5;
       	 $page_count = ceil($total/$rows);
       	 if(!$page || $page > $page_count) $page = 1;
         $start = ($page-1)*$rows;
         $no = $total-$start;
      ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 회원수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색 회원수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <form>
        <tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>이름</th>
          <th>아이디</th>
          <th>휴대폰</th>
          <th>이메일</th>
          <th width="5%">방문수</th>
          <th width="10%">SMS수신</th>
          <th width="10%">가입일</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
        </form>
		<?
		$sql = "select id,passwd,name,hphone,email,visit,reemail,wdate from wiz_member where id != '' ";

		if($sdate != "") 		$sql .= " and wdate > '$sdate'";
		if($edate != "") 		$sql .= " and wdate <= '$edate 23:59:59'";
		if($searchkey != "") $sql .= " and $searchopt like '%$searchkey%'";
		if($level != "") 		$sql .= " and level = '$level'";
		if($birthday == "Y") $sql .= " and birthday like '%$today'";
		if($memorial == "Y") $sql .= " and memorial like '%$today'";
		if($age != "")       $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
		if($address != "")   $sql .= " and address like '%$address%'";
		if($job != "")       $sql .= " and job = '$job'";
		if($marriage != "")  $sql .= " and marriage = '$marriage'";
		if($reemail == "N")	$sql .= " and reemail != 'N'";

		$sql .=" order by wdate desc";
		$send_sql = base64_encode($sql);
		$sql .=" limit $start, $rows";
		$result = query($sql) or error("sql error");

		while($row = sql_fetch_obj($result)){
			if($row->reemail == "N") $row->reemail = "아니오";
			else $row->reemail = "예";
		?>
	     <form>
        <input type="hidden" name="id" value="<?=$row->id?>">
        <tr>
          <td align="center" height="38"><?=$no?></td>
          <td align="center"><?=$row->name?></td>
          <td align="center"><?=$row->id?></td>
          <td align="center"><?=$row->hphone?></td>
          <td align="center"><?=$row->email?></td>
          <td align="center"><?=$row->visit?></td>
          <td align="center"><?=$row->reemail?></td>
          <td align="center"><?=substr($row->wdate,0,10)?> &nbsp;</td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
        </form>
     <?
     		$no--;
      }

    	if($total <= 0){
    	?>
    		<tr><td height=30 colspan=10 align=center>검색된 회원이 없습니다.</td></tr>
    		<tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>

      <br>

      <? print_pagelist($page, $lists, $page_count, "$param"); ?>

      <br>

      <form name="frm" action="message_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
      <input type="hidden" name="mode" value="send">
      <input type="hidden" name="msgsend" value="true">
      <input type="hidden" name="msgsql" value="<?=$send_sql?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;쪽지제목</td>
                <td width="85%" class="t_value" colspan="3">
                <input type="text" name="subject" size="60" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;쪽지내용</td>
                <td class="t_value" colspan="3">
				<?
				if($site_info['msg_editor_use'] == "Y"){
					$edit_name	  = "content";
					$edit_content = $msg_info['content'];
					include WIZHOME_PATH."/webedit/WIZEditor.html";
				}else{
				?>
				<textarea name="content" cols="85" rows="12" class="textarea" style="width:98%;word-break:break-all;"><?=$msg_info['content']?></textarea>
				<?
				}
				?>

                </td>
              </tr>
              <tr>
                <td height="25" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;첨부파일</td>
                <td class="t_value" colspan="3">
				<div class="filebox preview-image">
					<input class="input upload-name" value="파일선택" disabled="disabled">
					<label for="input-file">이미지 업로드</label>
					<input type="file" name="upfile" id="input-file" class="upload-hidden">
				</div>

                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><input type="submit" value="쪽지발송" class="base_btn reg"></td>
        </tr>
      </table>
	  </form>


<? include "../foot.php"; ?>
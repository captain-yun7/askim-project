<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>

<?
if($mode == "") $mode = "insert";
if($mode == "update"){
	$sql = "select * from wiz_level where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
}
?>
<script language="javascript">
<!--
function inputCheck(frm){
	if(frm.name.value == ""){
		alert("등급명을 입력하세요.");
		frm.name.focus();
		return false;
	}
}

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">등급관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">회원등급을 생성 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="level_save.php" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">

	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name">등급명</td>
                <td class="t_value" colspan="3"><input type="text" size="30" name="name" value="<?=$row->name?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">등급레벨</td>
                <td class="t_value" colspan="3">
                <?
                if($mode == "insert" || $row->level > 0){
                ?>
                  <select name="level" class="select">
                  <option value="1" <? if($row->level == "1") echo "selected"; ?>>1
                  <option value="2" <? if($row->level == "2") echo "selected"; ?>>2
                  <option value="3" <? if($row->level == "3") echo "selected"; ?>>3
                  <option value="4" <? if($row->level == "4") echo "selected"; ?>>4
                  <option value="5" <? if($row->level == "5") echo "selected"; ?>>5
                  <option value="6" <? if($row->level == "6") echo "selected"; ?>>6
                  <option value="7" <? if($row->level == "7") echo "selected"; ?>>7
                  <option value="8" <? if($row->level == "8") echo "selected"; ?>>8
                  <option value="9" <? if($row->level == "9") echo "selected"; ?>>9
                  <option value="10" <? if($row->level == "10") echo "selected"; ?>>10
                  <option value="11" <? if($row->level == "11") echo "selected"; ?>>11
                  <option value="12" <? if($row->level == "12") echo "selected"; ?>>12
                  <option value="13" <? if($row->level == "13") echo "selected"; ?>>13
                  <option value="14" <? if($row->level == "14") echo "selected"; ?>>14
                  <option value="15" <? if($row->level == "15") echo "selected"; ?>>15
                  <option value="16" <? if($row->level == "16") echo "selected"; ?>>16
                  <option value="17" <? if($row->level == "17") echo "selected"; ?>>17
                  <option value="18" <? if($row->level == "18") echo "selected"; ?>>18
                  <option value="19" <? if($row->level == "19") echo "selected"; ?>>19
                  <option value="20" <? if($row->level == "20") echo "selected"; ?>>20
                  </select>
                <?
                }else{
                ?>
                <input type="hidden" name="level" value="<?=$row->level?>">
                <?=$row->level?>
                <?
                }
                ?><div class="sub_tit_alt2"> 등급레벨 숫자가 클수록 낮은 등급입니다. </div>
                </td>
              </tr>
              <!--tr>
                <td class="t_name">접근권한</td>
                <td class="t_value" colspan="3">
                  <?
                  $permi_list = explode("/",$row->permi);
                  for($ii=0; $ii<count($permi_list); $ii++){
                     $tmp_permi[$permi_list[$ii]] = true;
                  }
                  ?>
                  <input type="checkbox" size="20" name="permi[]" value="00" <? if($tmp_permi["00"]==true) echo "checked"; ?>>관리자(인트라넷)접근
                  <input type="checkbox" size="20" name="permi[]" value="01" <? if($tmp_permi["01"]==true) echo "checked"; ?>>환경설정
                  <input type="checkbox" size="20" name="permi[]" value="02" <? if($tmp_permi["02"]==true) echo "checked"; ?>>기본정보
                  <input type="checkbox" size="20" name="permi[]" value="03" <? if($tmp_permi["03"]==true) echo "checked"; ?>>사내업무>
                  <input type="checkbox" size="20" name="permi[]" value="04" <? if($tmp_permi["04"]==true) echo "checked"; ?>>회원관리
                  <input type="checkbox" size="20" name="permi[]" value="05" <? if($tmp_permi["05"]==true) echo "checked"; ?>>게시판관리
                  <input type="checkbox" size="20" name="permi[]" value="06" <? if($tmp_permi["06"]==true) echo "checked"; ?>>마케팅분석
                </td>
              </tr-->
			  
			  <?
			  ##쇼핑몰 사용일경우에 할인타입,할인액 노출
			  $menu_array=explode("/",$site_info['menu_use']);
			  ?>
			  <?if(in_array("PRODUCT",$menu_array)){?>

              <tr>
                <td class="t_name">할인타입</td>
                <td class="t_value">
                  <span style="vertical-align: middle"><input type="radio" name="distype" value="W" <? if($row->distype == "" || $row->distype == "W") echo "checked"; ?>></span>원 (구매시 x원 할인 혜택을 드립니다.)<br>
                  <span style="vertical-align: middle"><input type="radio" name="distype" value="P" <? if($row->distype == "P") echo "checked"; ?>></span>퍼센트 (구매액의 x%할인혜택을드립니다.)
                </td>
              </tr>
              <tr>
                <td class="t_name">할인액</td>
                <td class="t_value"><input type="text" name="discount" value="<?=$row->discount?>" class="input"> 원 또는 %</td>
              </tr>

			  <?
			  ##쇼핑몰 사용 안할 경우
			  }else{?>

				<input type="hidden" name="distype" value="<?=$row->distype?>">
				<input type="hidden" name="discount" value="<?=$row->discount?>">

			  <?}?>
              <tr>
                <td class="t_name">설명</td>
                <td class="t_value" colspan="3"><textarea name="memo" rows="6" cols="60" class="textarea"><?=$row->memo?></textarea></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
		  <input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='level_list.php?<?=$menucodeParam
?>';">

          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>
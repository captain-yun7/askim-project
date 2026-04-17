<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<?
$sql = "select * from wiz_meminfo";
$result = query($sql) or error("sql error");
$mem_info = sql_fetch_arr($result);
$mem_info['agreement'] = stripslashes($mem_info['agreement']);
$mem_info['safeinfo'] = stripslashes($mem_info['safeinfo']);
?>
<? include "../head.php"; ?>
<script language="javascript">
<!--
function inputCheck(frm){

   if(frm.agreement.value == ""){
      alert("가입약관을 입력하세요");
      frm.agreement.focus();
      return false;
   }
   if(frm.safeinfo.value == ""){
      alert("개인정보 수집·이용에 대해 동의 내용을 입력하세요");
      frm.safeinfo.focus();
      return false;
   }

}
-->
</script>
</head>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif" /></td>
          <td valign="bottom" class="tit">약관·개인정보 수집·이용 동의</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">약관·개인정보 수집·이용에 대해 동의를 설정합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="member_save.php?<?=$param?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="config">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">가입약관</td>
                <td width="85%" class="t_value" colspan="3">
                <textarea name="agreement" rows="20" class="txtfullp" style="height: 300px"><?=$mem_info['agreement']?></textarea>
                </td>
              </tr>
              <tr>
                <td class="t_name">개인정보 수집·이용에 대한 동의</td>
                <td class="t_value" colspan="3">
                <textarea name="safeinfo" rows="20" class="txtfullp" style="height: 300px"><?=$mem_info['safeinfo']?></textarea>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;<!-- <input type="button" value="취소" class="base_btn gray" onClick="history.back();"> -->

          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>
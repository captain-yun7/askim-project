<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>

<?
if($mode == "") $mode = "level_insert";
if($mode == "level_update"){
	$sql = "select * from wiz_admin_lev where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
}
if($mode != "level_insert" && $mode != "level_update") { 
	error("잘못된 접근입니다");
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
          <td valign="bottom" class="tit">관리자등급관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">관리자등급을 생성 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="twcenter_save.php" onSubmit="return inputCheck(this);" method="post">
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
                <td class="t_name">접근권한</td>
                <td class="t_value" colspan="3">
                  <?
				  
                  $permi_list = explode("/",strtoupper($row->permi ?? ''));
				  $menu_use = explode("/", $site_info['menu_use']);
				  foreach($menu_use as $k=>$menu) {
					  $menu = trim($menu);
					  if($menu == "") continue;
                  ?>
                  <label for="permi<?=$k?>" style="display:inline-block;width:100px;"><input type="checkbox" size="20" name="permi[]" id="permi<?=$k?>" value="<?=$menu?>" <? if(@in_array($menu, $permi_list)) echo "checked"; ?>><?=$admin_menu[$menu]?></label>
				<?
					  if($k == 5) echo "<BR>";
				  }
				  ?>
                </td>
              </tr>

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
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='twcenter_level.php?<?=$menucodeParam
?>';">

          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>
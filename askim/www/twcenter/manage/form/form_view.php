<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$param = "code=".$code."&searchopt=".$searchopt."&searchkey=".$searchkey."&page=".$page."&".$menucodeParam;

$sql = "select * from wiz_form where idx = '$idx'";
$result = query($sql) or error("sql error");
$form_info = sql_fetch_arr($result);
?>
<script language="JavaScript" type="text/javascript">
<!--
function delConfirm(){
	if(confirm("삭제 하시겠습니까?")){
		document.location = "form_save.php?mode=delete&idx=<?=$idx?>&<?=$param?>"; 
	}
}
//-->
</script>

<? include "../head.php"; ?>  
      
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr> 
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제목</td>
                <td class="t_value"><?=$form_info['subject']?></td>
              </tr>
              <tr> 
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;처리상태</td>
                <td class="t_value"><?=$form_info['status']?></td>
              </tr>
              <tr> 
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;첨부파일</td>
                <td class="t_value">
                	<a href="down.php?idx=<?=$idx?>&no=1"><?=$form_info['upfile1_name']?></a> 
                	<a href="down.php?idx=<?=$idx?>&no=2"><?=$form_info['upfile2_name']?></a> 
                	<a href="down.php?idx=<?=$idx?>&no=3"><?=$form_info['upfile3_name']?></a>
                </td>
              </tr>
              <tr> 
                <td height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;작성내용</td>
                <td class="t_value"><?=$form_info['content']?></td>
              </tr>
            </table></td>
        </tr>
      </table><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr><td><input type="button" class="search_default2" value="목록" onclick="document.location='form_list.php?<?=$param?>';"></td>
      <td width="100%" align="center">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="60"><input type="button" class="search_btn2" value=" 수정 " onClick="document.location='form_input.php?idx=<?=$idx?>'"></td>
          <td width="10">&nbsp;</td>
          <td width="54" align="right"><input type="button" class="search_default2" value="삭제" onClick="delConfirm();"></td>
        </tr>
      </form>
      </table>
      </td>
      </tr>
      </table>
      
<? include "../foot.php"; ?>
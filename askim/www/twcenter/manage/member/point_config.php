<? include_once "../../common.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>
<script language="javascript">
<!--
function inputCheck(frm){

}
-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">포인트설정</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">활동별 포인트를 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="point_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="config">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">회원가입 포인트</td>
                <td width="35%" class="t_value"><input type="text" name="join_point" value="<?=$site_info['join_point']?>" class="input"></td>
                <td width="15%" class="t_name">로그인 포인트</td>
                <td width="35%" class="t_value"><input type="text" name="login_point" value="<?=$site_info['login_point']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">게시판 보기 포인트</td>
                <td class="t_value"><input type="text" name="view_point" value="<?=$site_info['view_point']?>" class="input"></td>
                <td class="t_name">게시판 글쓰기 포인트</td>
                <td class="t_value"><input type="text" name="write_point" value="<?=$site_info['write_point']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">게시판 다운로드 포인트</td>
                <td class="t_value"><input type="text" name="down_point" value="<?=$site_info['down_point']?>" class="input"></td>
                <td class="t_name">게시판 덧글 포인트</td>
                <td class="t_value"><input type="text" name="comment_point" value="<?=$site_info['comment_point']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">게시판 추천 포인트</td>
                <td class="t_value"><input type="text" name="recom_point" value="<?=$site_info['recom_point']?>" class="input"></td>
                <td class="t_name">쪽지 보내기 포인트</td>
                <td class="t_value"><input type="text" name="msg_point" value="<?=$site_info['msg_point']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">포인트가 없을경우<br>경고메세지</td>
                <td class="t_value" colspan="3"><input type="text" name="point_msg" value="<?=$site_info['point_msg']?>" class="input" size="85"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
          	<input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
          	<img src="../image/btn_cancel_l.gif" style="cursor:hand" onClick="history.back();">
          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>
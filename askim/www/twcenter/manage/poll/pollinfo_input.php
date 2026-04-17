<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$page_name = "설문관리";
$page_desc = "설문정보를 등록/수정합니다.";
$navi_name = " 설문관리 > 설문정보";

$param = "page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey."&".$menucodeParam;

if($mode == "") $mode = "insert";

if($mode == "insert"){

	$poll_info['permsg'] = "권한이 없습니다.";

}else if($mode == "update"){

	$sql = "select * from wiz_pollinfo where code = '$code'";
	$result = query($sql) or error("sql error");
	$poll_info = sql_fetch_arr($result);

}
?>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(form){

   if(form.code.value == ""){
      alert('설문코드를 입력하세요.');
      form.code.focus();
      return false;
   } else if(!check_Char(form.code.value)) {
   		alert('설문코드는 특수문자를 사용할 수 없습니다.');
      form.code.focus();
   		return false;
   }
   if(form.title.value == ""){
      alert('설문명을 입력하세요.');
      form.title.focus();
      return false;
   }

}

//-->
</script>

<? include "../head.php"; ?>

      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">설문관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">설문을 추가/삭제, 상세기능을 설정합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="pollinfo_save.php?<?=$param?>" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">설문코드 <font color=red>*</font></td>
                <td width="35%" class="t_value">
                  <input name="code" type="text" size="30" value="<?=$poll_info['code']?>" maxlength="30" <? if($mode == "update") echo "readonly"; ?> class="input">
                </td>
                <td width="15%" class="t_name">설문명 <font color=red>*</font></td>
                <td width="35%" class="t_value">
                  <input name="title" type="text" size="30" value="<?=$poll_info['title']?>" maxlength="30" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">권한</td>
                <td class="t_value" colspan="3">
                  <table width="98%" border="0" cellspacing="1" cellpadding="6" class="t_style">
                    <tr>
                      <td width="25%" class="t_name" align="center" height="35">목록보기</td>
                      <td width="25%" class="t_name" align="center">내용보기</td>
                      <td width="25%" class="t_name" align="center">설문참여</td>
                      <td width="25%" class="t_name" align="center">코멘트쓰기</td>
                    </tr>
                    <tr>
                      <td align="center" height="30" style="padding:5px 0">
                        <select name="lpermi" class="select">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="rpermi" class="select">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="apermi" class="select">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="cpermi" class="select">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                  <script type="text/javascript">
                    <!--
					$(function(){
						var lpermi = "<?=$poll_info['lpermi']?>";
						var rpermi = "<?=$poll_info['rpermi']?>";
						var apermi = "<?=$poll_info['apermi']?>";
						var cpermi = "<?=$poll_info['cpermi']?>";

						$("select[name=lpermi]").val(lpermi).attr("selected", "selected");
						$("select[name=rpermi]").val(rpermi).attr("selected", "selected");
						$("select[name=apermi]").val(apermi).attr("selected", "selected");
						$("select[name=cpermi]").val(cpermi).attr("selected", "selected");

					});
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td class="t_name">권한이 없을경우</td>
                <td class="t_value" colspan="3">
                	경고메세지 : <input name="permsg" type="text" size="30" value="<?=$poll_info['permsg']?>" class="input">&nbsp;
                	경고후 이동페이지 : <input name="perurl" type="text" size="30" value="<?=$poll_info['perurl']?>" class="input">
                </td>
              </tr>
				<tr>
					<td width="15%" height="10" align="left" class="t_name">브라우저 타이틀</td>
					<td width="85%" class="t_value" colspan="3"><input name="browser_title" type="text" size="60" value="<?=$poll_info['browser_title']?>" class="input"></td>
				</tr>
				<tr>
					<td width="15%" align="left" class="t_name">메타네임(Description)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?=$poll_info['searchkey_de']?></textarea>
						<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
						<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
					</td>
				  </tr>
				  <tr>
					<td width="15%" align="left" class="t_name">메타네임(Classification)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?=$poll_info['searchkey_cl']?></textarea>
						<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
					</td>
				  </tr>

				  <tr>
					<td width="15%" align="left" class="t_name">메타네임(keywords)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey" rows="3" cols="120" class="textarea"><?=$poll_info['searchkey']?></textarea>
						<div class="sub_tit_alt_red">※ 해당 항목은 네이버 검색에만 적용되며, 구글에선 참고용으로만 활용합니다.</div>
						<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 활용해 검색된다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며, 일부 반영이 안될 수 있습니다.</div>
						<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
						<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
					</td>
				  </tr>
              <tr>
                <td class="t_name">스킨</td>
                <td colspan="3" class="t_value">
                <select name="skin" class="select">
                <?
                $dh = opendir("../../poll/skin");
                while(($file = readdir($dh)) !== false){
                	if($file != "." && $file != ".."){
                		$file_list[] = $file;
                	}
                }
                sort ($file_list); reset ($file_list);
                for($ii=0;$ii<count($file_list);$ii++){
                ?>
                <option value="<?=$file_list[$ii]?>"><?=$file_list[$ii]?></option>
                <?
                }
                ?>
                </select>
                <script language="javascript">
                <!--
				$(function(){
					var skin = "<?=$poll_info['skin']?>";
					$("select[name=skin]").val(skin).attr("selected", "selected");
				});
                -->
                </script>
                </td>
              </tr>
              <tr>
                <td width="15%" class="t_name">스팸글체크기능</td>
                <td width="35%" class="t_value">
                	<span style="vertical-align: middle"><input type="radio" name="spam_check" value="Y" <? if($poll_info['spam_check'] == "Y") echo "checked"; ?>></span>사용함
                	<span style="vertical-align: middle"><input type="radio" name="spam_check" value="N" <? if($poll_info['spam_check'] == "N" || $poll_info['spam_check'] == "") echo "checked"; ?>></span>사용안함
                </td>
                <td width="15%" class="t_name">코멘트 허용</td>
                <td width="35%" class="t_value">
                  <span style="vertical-align: middle"><input type="radio" name="comment" value="Y" <? if($poll_info['comment'] == "Y") echo "checked"; ?>></span>허용함
                  <span style="vertical-align: middle"><input type="radio" name="comment" value="N" <? if($poll_info['comment'] == "N" || empty($poll_info['comment'])) echo "checked"; ?>></span>허용안함
                </td>
              </tr>
              <tr>
                <td class="t_name">날짜형식(목록페이지)</td>
                <td class="t_value">
                	<select name="datetype_list" class="select">
                		<option value="">:: 목록페이지 :: </option>
                		<option value="%y.%m.%d"><?= date('y.m.d') ?></option>
                		<option value="%y/%m/%d"><?= date('y/m/d') ?></option>
                		<option value="%y-%m-%d"><?= date('y-m-d') ?></option>
                		<option value="%Y.%m.%d"><?= date('Y.m.d') ?></option>
                		<option value="%Y/%m/%d"><?= date('Y/m/d') ?></option>
                		<option value="%Y-%m-%d"><?= date('Y-m-d') ?></option>
                		<option value="%Y년 %m월 %d일"><?= date('Y년 m월 d일') ?></option>
                		<option value="%Y-%m-%d %H:%i"><?= date('Y-m-d H:i') ?></option>
                		<option value="%Y-%m-%d %H:%i %p"><?= date('Y-m-d h:i A') ?></option>
                	</select>
                  <script language="javascript">
                    <!--
					$(function(){
						var datetype_list = "<?=$poll_info['datetype_list']?>";
						$("select[name=datetype_list]").val(datetype_list).attr("selected", "selected");
					});
                    -->
                  </script>
                </td>
                <td class="t_name">날짜형식(보기페이지)</td>
                <td class="t_value">
                	<select name="datetype_view" class="select">
                		<option value="">:: 보기페이지 :: </option>
                		<option value="%y.%m.%d"><?= date('y.m.d') ?></option>
                		<option value="%y/%m/%d"><?= date('y/m/d') ?></option>
                		<option value="%y-%m-%d"><?= date('y-m-d') ?></option>
                		<option value="%Y.%m.%d"><?= date('Y.m.d') ?></option>
                		<option value="%Y/%m/%d"><?= date('Y/m/d') ?></option>
                		<option value="%Y-%m-%d"><?= date('Y-m-d') ?></option>
                		<option value="%Y년 %m월 %d일"><?= date('Y년 m월 d일') ?></option>
                		<option value="%Y-%m-%d %H:%i"><?= date('Y-m-d H:i') ?></option>
                		<option value="%Y-%m-%d %H:%i %p"><?= date('Y-m-d h:i A') ?></option>
                	</select>
                  <script language="javascript">
                    <!--
					$(function(){
						var datetype_view = "<?=$poll_info['datetype_view']?>";
						$("select[name=datetype_view]").val(datetype_view).attr("selected", "selected");
					});
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td class="t_name">페이지출력수 <font color=red>*</font></td>
                <td class="t_value"><input name="poll_rows" type="text" value="<? if($poll_info['poll_rows'] == "") echo "20"; else echo $poll_info['poll_rows']; ?>" class="input"></td>
                <td class="t_name">리스트출력수 <font color=red>*</font></td>
                <td class="t_value"><input name="lists" type="text" value="<? if($poll_info['lists'] == "") echo "5"; else echo $poll_info['lists']; ?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">new 기간설정</td>
                <td class="t_value"><input name="newc" type="text" value="<? if($poll_info['newc'] == "") echo "2"; else echo $poll_info['newc']; ?>" class="input"></td>
                <td class="t_name">제목 글자수</td>
                <td class="t_value"><input name="subject_len" type="text" value="<?=$poll_info['subject_len'];?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">욕설,비방글 필터링</td>
                <td class="t_value" colspan="3">
                  <span style="vertical-align: middle"><input type="checkbox" name="abuse" value="Y" <? if($poll_info['abuse'] == "Y") echo "checked"; ?>></span>사용함
                  <textarea name="abtxt" class="txt txtfullp2 tip_br5"><?=trim($poll_info['abtxt'] ?? "")?></textarea>
				  <div class="sub_tit_alt2"> 공백없이 단어를 입력하시고, 단어와 단어 사이에는 콤마(,)로 구분하세요.</div>
              </tr>
            </table>
           </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg" >&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='pollinfo_list.php?<?=$param?>';">
          </td>
      </table>
	  </form>
<? include "../foot.php"; ?>
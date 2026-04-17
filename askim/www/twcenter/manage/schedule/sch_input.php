<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
if($mode == "") $mode = "insert";

if($mode == "insert"){

	$sch_info['simgsize'] = "120";
	$sch_info['mimgsize'] = "500";

}else if($mode == "update"){
	$sql = "select * from wiz_bbsinfo where code = '$code'";
	$result = query($sql) or error("sql error");
	$sch_info = sql_fetch_arr($result);
}
?>
<? include "../head.php"; ?>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

   if(frm.code.value == ""){
      alert('일정 영문명(db명)을 입력하세요.');
      frm.code.focus();
      return false;
   } else if(!check_Char(frm.code.value)) {
   		alert('일정 영문명(db명)은 특수문자를 사용할 수 없습니다.');
      frm.code.focus();
   		return false;
   }

   if(frm.title.value == ""){
      alert('일정 한글명 입력하세요.');
      frm.title.focus();
      return false;
   }
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">일정관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">일정을 추가/삭제, 상세기능을 설정합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="sch_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name">영문명(db명) <font color=red>*</font></td>
                <td class="t_value" colspan="3"><input name="code" type="text" size="30" value="<?=$sch_info['code']?>" <? if($mode == "update") echo "readonly"; ?> class="input"></td>
              </tr>
              <tr>
                <td class="t_name">한글명 <font color=red>*</font></td>
                <td class="t_value" colspan="3"><input name="title" type="text" size="30" value="<?=$sch_info['title']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">일정관리자</td>
                <td class="t_value" colspan="3">아이디를 쉼표로 분리 예)Operator,handler,manager<br><input name="bbsadmin" type="text" size="60" value="<?=$sch_info['bbsadmin']?>" class="input"></td>
              </tr>
				<tr>
					<td class="t_name">브라우저 타이틀</td>
					<td class="t_value" colspan="3"><input name="browser_title" type="text" size="60" value="<?=$sch_info['browser_title']?>" class="input"></td>
				</tr>
				<tr>
					<td width="15%" align="left" class="t_name">메타네임(Description)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?=$sch_info['searchkey_de']?></textarea>
						<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
						<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
					</td>
				  </tr>
				  <tr>
					<td width="15%" align="left" class="t_name">메타네임(Classification)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?=$sch_info['searchkey_cl']?></textarea>
						<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
					</td>
				  </tr>

				  <tr>
					<td width="15%" align="left" class="t_name">메타네임(keywords)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey" rows="3" cols="120" class="textarea"><?=$sch_info['searchkey']?></textarea>
						<div class="sub_tit_alt_red">※ 해당 항목은 네이버 검색에만 적용되며, 구글에선 참고용으로만 활용합니다.</div>
						<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 활용해 검색된다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며, 일부 반영이 안될 수 있습니다.</div>
						<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
						<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
					</td>
				  </tr>
              <tr>
                <td width="15%" class="t_name">자동 비밀글</td>
                <td width="35%" class="t_value">
                  <span style="vertical-align: middle"><input type="checkbox" name="privacy" value="Y" <? if($sch_info['privacy'] == "Y") echo "checked"; ?>></span>작성자와 운영자만 열람가능
                </td>
                <td width="15%" class="t_name">일정스킨</td>
                <td width="35%" class="t_value">
                <select name="skin" class="select">
                <?
                $dh = opendir("../../schedule/skin");
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
                </td>
              </tr>
              <tr>
                <td class="t_name">권한</td>
                <td class="t_value" colspan="3">
                  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
                    <tr class="t_name">
                      <td width="20%" align="center" height="25" class="t_name">목록보기</td>
                      <td width="20%" align="center" class="t_name">내용보기</td>
                      <td width="20%" align="center" class="t_name">글쓰기</td>
                      <!--td width="20%" align="center">답글쓰기</td-->
                      <td width="20%" align="center" class="t_name">코멘트쓰기</td>
                    </tr>
                    <tr>
                      <td align="center"  height="30" style="padding:5px 0">
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
                        <select name="wpermi" class="select">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <input type="hidden" name="apermi" value="0">
                      <!--td align="center">
                        <select name="apermi">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td-->
                      <td align="center">
                        <select name="cpermi" class="select">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                  <script language="javascript">
                    <!--
					$(function(){
						var skin = "<?=$sch_info['skin']?>";
						$("select[name=skin]").val(skin).attr("selected", "selected");

						var lpermi = "<?=$sch_info['lpermi']?>";
						var rpermi = "<?=$sch_info['rpermi']?>";
						var wpermi = "<?=$sch_info['wpermi']?>";
						var cpermi = "<?=$sch_info['cpermi']?>";

						$("select[name=lpermi]").val(lpermi).attr("selected", "selected");
						$("select[name=rpermi]").val(rpermi).attr("selected", "selected");
						$("select[name=wpermi]").val(wpermi).attr("selected", "selected");
						$("select[name=cpermi]").val(cpermi).attr("selected", "selected");

					});
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td rowspan="2" class="t_name">권한이 없을경우</td>
                <td class="t_value" colspan="3">
                	경고메세지 : <input name="permsg" type="text" size="30" value="<?=$sch_info['permsg']?>" class="input">&nbsp;
                	경고후 이동페이지 : <input name="perurl" type="text" size="30" value="<?=$sch_info['perurl']?>" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_value" colspan="3">
                	<span style="vertical-align: middle"><input type="radio" name="btn_view" value="N" <? if($sch_info['btn_view'] == "N" || $sch_info['btn_view'] == "") echo "checked"; ?>></span> 글쓰기 버튼이 보이지 않음
                	<span style="vertical-align: middle"><input type="radio" name="btn_view" value="Y" <? if($sch_info['btn_view'] == "Y") echo "checked"; ?>></span> 글쓰기 버튼이 보이고 클릭 시 경고창
                </td>
              </tr>
              <tr>
                <td class="t_name">이미지크기</td>
                <td class="t_value" colspan="3">
                	목록페이지  : <input name="simgsize" type="text" size="9" value="<?=$sch_info['simgsize']?>" class="input">픽셀 &nbsp;
                	보기페이지  : <input name="mimgsize" type="text" size="9" value="<?=$sch_info['mimgsize']?>" class="input">픽셀
                </td>
              </tr>
              <tr>
                <td class="t_name">이미지파일</td>
                <td class="t_value" colspan="3">
                	<span style="vertical-align: middle"><input type="checkbox" name="imgview" value="N" <? if($sch_info['imgview'] == "N") echo "checked"; ?>></span>첨부파일이 이미지인 경우 보기 페이지에서 이미지 감추기
                </td>
              </tr>
              <tr>
                <td class="t_name">이미지 첨부파일 정렬</td>
                <td class="t_value">
                	<span style="vertical-align: middle"><input type="radio" name="img_align" value="LEFT" <? if($sch_info['img_align'] == "LEFT" || $sch_info['img_align'] == "") echo "checked"; ?>></span> 좌측정렬
                	<span style="vertical-align: middle"><input type="radio" name="img_align" value="CENTER" <? if($sch_info['img_align'] == "CENTER") echo "checked"; ?>></span> 중앙정렬
                	<span style="vertical-align: middle"><input type="radio" name="img_align" value="RIGHT" <? if($sch_info['img_align'] == "RIGHT") echo "checked"; ?>></span> 우측정렬
                </td>
                <td class="t_name">날짜형식(보기페이지)</td>
                <td class="t_value">
                	<select name=datetype_view class="select">
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
					var datetype_view = "<?=$sch_info['datetype_view']?>";
					$("select[name=datetype_view]").val(datetype_view).attr("selected", "selected");
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td height="30" class="t_name">웹에디터</td>
                <td class="t_value">
                	<span style="vertical-align: middle"><input type="radio" name="editor" value="Y" <? if($sch_info['editor'] == "Y") echo "checked"; ?>></span>사용함
                  <span style="vertical-align: middle"><input type="radio" name="editor" value="N" <? if($sch_info['editor'] == "N" || $sch_info['editor'] == "") echo "checked"; ?>></span>사용안함
                </td>
                <td height="30" class="t_name">코멘트 허용</td>
                <td class="t_value">
                  <span style="vertical-align: middle"><input type="radio" name="comment" value="Y" <? if($sch_info['comment'] == "Y") echo "checked"; ?>></span>허용함
                  <span style="vertical-align: middle"><input type="radio" name="comment" value="N" <? if($sch_info['comment'] == "N" || empty($sch_info['comment'])) echo "checked"; ?>></span>허용안함
                </td>
              </tr>
              <tr>
                <td height="30" class="t_name">파일업로드</td>
                <td class="t_value">
                	<select name="upfile" class="select">
                		<option value="0">사용안함</option>
                		<option value="1">1개</option>
                		<option value="2">2개</option>
                		<option value="3">3개</option>
                		<option value="4">4개</option>
                		<option value="5">5개</option>
                	</select>
                  <script language="javascript">
                    <!--
					var upfile = "<?=$sch_info['upfile']?>";
					$("select[name=upfile]").val(upfile).attr("selected", "selected");
                    -->
                  </script>
                </td>
                <td height="30" class="t_name">동영상</td>
                <td class="t_value">
                	<select name="movie" class="select">
                		<option value="0">사용안함</option>
                		<option value="1">1개</option>
                		<option value="2">2개</option>
                		<option value="3">3개</option>
                	</select>
                  <script language="javascript">
                    <!--
					var movie = "<?=$sch_info['movie']?>";
					$("select[name=movie]").val(movie).attr("selected", "selected");
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td class="t_name">스팸글체크기능</td>
                <td class="t_value">
                	<span style="vertical-align: middle"><input type="radio" name="spam_check" value="Y" <? if($sch_info['spam_check'] == "Y") echo "checked"; ?>></span>사용함
                	<span style="vertical-align: middle"><input type="radio" name="spam_check" value="N" <? if($sch_info['spam_check'] == "N" || $sch_info['spam_check'] == "") echo "checked"; ?>></span>사용안함
                </td>
                <td height="30" class="t_name">추천기능</td>
                <td class="t_value">
                	<span style="vertical-align: middle"><input type="radio" name="recom" value="Y" <? if($sch_info['recom'] == "Y") echo "checked"; ?>></span>사용함
                  <span style="vertical-align: middle"><input type="radio" name="recom" value="N" <? if($sch_info['recom'] == "N" || empty($sch_info['recom'])) echo "checked"; ?>></span>사용안함
                </td>
              </tr>
              <tr>
                <td class="t_name">욕설,비방글<br>필터링</td>
                <td class="t_value" colspan="3">
                   <input type="checkbox" name="abuse" value="Y" <? if($sch_info['abuse'] == "Y") echo "checked"; ?>>사용함<br>
                  <textarea name="abtxt" class="txt txtfullp2"><?=$sch_info['abtxt']?></textarea>
				  <div class="sub_tit_alt2"> 공백없이 단어를 입력하시고, 단어와 단어사이에는 콤마(,)로 구분하세요.</div>
              </tr>
            </table></td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='sch_list.php?page=<?=$page?>&<?=$menucodeParam?>';">
          </td>
        </tr>
      </table>
	  </form>

		<br>
		<table width="100%" border="0" cellspacing="10" cellpadding="8" bgcolor="9d9d9d" align="center">
			<tr>
				<td align="center">
					<table width="100%" border="0" cellspacing="0" cellpadding="6">
						<tr>
							<td><img src="../image/check_tit.gif" width="75" height="19" /></td>
						</tr>
						<tr>
							<td class="chk_alt">
							- 영문명은 반드시 영문으로 작성하고 변경이 불가합니다.<br>
							- 권한설정은 각 상황별 회원분류에따라 접근권한을 설정합니다.<br>
							- 욕설,비방글 설정을 통하여 글 작성시 욕설 비방글을 방지할 수 있습니다.
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>


<? include "../foot.php"; ?>
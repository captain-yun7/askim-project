<script>
	function set_mainview_display(code, mainview, mainview_count) {
		if(!mainview) {
			mainview = "n";
		}
		if(!mainview_count) {
			mainview_count = 1;
		}

		var set_data = {
			"code" : code,
			"mainview" : mainview,
			"mainview_count" : mainview_count
		};
		$.ajax({
			url : "/admin/auth/board_mainview",
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert("변경되었습니다.");
							location.reload();
						} else { //error
							alert(result.error);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
	}

	function set_adminview_display(code, adminview) {
		if(!adminview) {
			adminview = "n";
		}

		var set_data = {
			"code" : code,
			"adminview" : adminview,
		};
		$.ajax({
			url : "/admin/auth/board_adminview",
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert("변경되었습니다.");
							location.reload();
						} else { //error
							alert(result.error);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
	}

    function board_delete(code) {
        if(!code) {
            alert("게시판을 선택해주세요.");
            return false;
        }

        if(!confirm("정말로 삭제하시겠습니까?")) {
            return false;
        }

        var set_data = {
            "code" : code,
        };

        $.ajax({
			url : "/admin/auth/board_delete",
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert("삭제되었습니다.");
							location.reload();
						} else { //error
							alert(result.error);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
    }

	$('#leftmenu >ul > li:nth-of-type(4)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>게시판 관리</h2>
		<div class="btn_right">
			<a href="board_manage_reg" class="btn point new_plus">+ 게시판 생성</a>
		</div><!--btn_right-->
	</div>
	<div class="table_list">
	  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableA">
		<colgroup>
		  <col width="7%" />
		  <col width="*" />
		  <col width="22%" />
		  <col width="14%" />
		  <col width="13%" />
		</colgroup>
		<thead>
		  <tr>
			<th scope="col">No.</th>
			<th scope="col">게시판명 (게시판 코드)</th>
			<th scope="col">메인노출</th>
			<th scope="col">사용유무</th>
			<th scope="col">관리</th>
		  </tr>
		</thead>
		<tbody id='divList'>
		<?php if(isset($board_manage_list)) : ?>
			<?php foreach($board_manage_list as $key => $value) : ?>
				<tr>
					<td><?=$total_rows_manage - $key - $offset?></td>
					<td align="left"><?=$value["name"]?> (<?=$value["code"]?>)</td>
					<td align="left">
						<label class="bbs_manage"><input type="checkbox" onchange="set_mainview_display('<?=$value["code"]?>', this.value);" value="<?if($value["mainview"] == "n") :?>y<?else :?>n<?endif?>" <?if($value["mainview"] == "y") :?>checked<?endif?>> 노출</label>
						<div class="bbs_manage <?php if($value["mainview"] != "y") :?>hide<?php endif?>">
							노출 게시글 개수
							<select onChange="set_mainview_display('<?=$value["code"]?>', '<?=$value["mainview"]?>', this.value);">
								<?php for($i = 0; $i <= 10; $i++) : ?>
									<option value="<?=$i?>" <?php if($value["mainview_count"] == $i) :?>selected<?php endif?>><?=$i?></option>
								<?php endfor ?>
							</select>
						</div>
					</td>

					<td align="left">
						<select name="adminview" onchange="set_adminview_display('<?=$value["code"]?>', this.value);">
							<option value="y" <?if($value["adminview"] == "y") :?>selected<?php endif?> >사용</option>
							<option value="n" <?if($value["adminview"] == "n") :?>selected<?php endif?> >미사용</option>
						</select>
                        <!-- 초기 게시판 제외 -->
                        <?php if(!in_array($value["code"], array("inquiry", "gallery", "notice", "review"))) : ?>
						    <a href="javascript://" onclick="board_delete('<?=$value["code"]?>')" class="btn_mini">삭제</a>
                        <?php endif ?>
					</td>
					<td>
						<a href="/board/board_list?code=<?=$value["code"]?>" class="btn_mini" target="_blank">보기</a>
						<a href="board_manage_reg?code=<?=$value["code"]?>" class="btn_mini on">수정</a>
					</td>
				</tr>
			<?php endforeach ?>
		<?php endif ?>
		</tbody>
	  </table>
	</div><!--table_list-->
	<div class="btn_paging">
		<?=$pagination?>
	</div><!--btn_paging-->
	<div class="terms_privecy_box">
		<dl>
			<dt>- 게시판 아이디값 주의사항! (필독)</dt>
			<dd>
			게시판 아이디 값에 <em class="point">"manage"</em>, <em class="point">"global"</em>, <em class="point">"file"</em>, <em class="point">"comment"</em> 문자열을 단독으로 사용하지 마세요.<br/>이미 문자열을 단독으로 게시판을 생성 후 사용중일 경우, 해당 게시판은 <em class="point">절대로 삭제</em>하시면 안됩니다.
			</dd>
		</dl>
		<dl class="mt20">
			<dt>- 게시판을 메인 프론트페이지(홈페이지)에서 보이게하려면?</dt>
			<dd>
			게시판 리스트 <em class="point">"메인노출"</em> 을 <em class="point">"노출함"</em> 체크 후 노출할 <em class="point">게시글 수</em>를 정해주세요. <br/>
			메인페이지 진열 치환코드를 <em class="point">홈페이지 스킨명/index.html</em> 안에 <em>html 소스작업</em>을 하여 넣어주셔야 합니다. (자세한 내용은 가이드를 참조해주세요.)
			</dd>
		</dl>
		<dl class="mt20">
			<dt>- 게시판 "사용유무"는 어떤 기능인가요? </dt>
			<dd>게시판 사용안함 설정 시 "게시글관리"에서 해당 게시판이 노출되지 않으며, 메인 상품 진열이 불가합니다. <br>메인에 진열하는 상품인데 "사용안함" 설정시, 홈페이지 접속이 불가능합니다.</dd>
		</dl>
		<dl class="mt20">
			<dt>- 게시판 삭제 시 주의사항 </dt>
			<dd>기본으로 생성되어있는 notice, gallery, review 게시판 삭제 시 사이트 접속이 불가능합니다. </dd>
		</dl>
	</div>
</div>
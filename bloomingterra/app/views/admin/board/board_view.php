<script type="text/javascript" src="/lib/admin/js/admin_board.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/admin/css/cssreset-context-min.css"><!-- 에디터 css -->
<style>
#tag { display: flex; }
.tags { margin-right: 10px; }
</style>
<script>
	var Common_Board = new common_board({
		code : "<?=$board_info["code"]?>",
		no : "<?=$this->input->get("no", true)?>"
	});
	$(function() {
		$("form[name='comment_frm']").validate({
			rules : {
				name : {required : true},
				//content : {required : {depends : function(){return !getSmartEditor("comment");}}},
				content : {required : true},
				file : {}
			}, messages : {
				name : {required : "작성자를 입력해주세요."},
				content : {required : "내용을 입력해주세요."},
				file : {}
			}
		});

	});

	function comment_modify_display(idx, is_display) {
		var display_comment = $("#display_comment_"+ idx);

		if(is_display) {
			$("#modify_comment_content_"+ idx +", #modify_comment_btn_"+ idx, display_comment).removeClass("hide");
			$("#view_comment_btn_"+ idx + ", #view_comment_content_"+ idx, display_comment).addClass("hide");
		} else {
			$("#modify_comment_content_"+ idx +", #modify_comment_btn_"+ idx, display_comment).addClass("hide");
			$("#view_comment_btn_"+ idx + ", #view_comment_content_"+ idx, display_comment).removeClass("hide");
		}
	}

	function comment_modify_check(idx) {
		var display_comment = $("#display_comment_"+ idx);
		$("[name='mode_"+ idx +"']", display_comment).val("modify");

		comment_modify_display(idx, true);
	}

	function comment_delete_check(idx, is_secret) {
		var display_comment = $("#display_comment_"+ idx);
		$("[name='mode_"+ idx +"']", display_comment).val("delete");

		if(is_secret == 'y') {
			Common_Board.board_comment_delete(idx);
		} else {
			$("#layer_password_"+ idx, display_comment).removeClass("modify").removeClass("hide").find(":password").focus();
		}
	}
	//2020-02-24 Inbet Matthew 삭제시 컨펌 기능 추가
	function board_delete(code, no) {
		if(!confirm("선택한 게시글을 삭제하시겠습니까? 삭제된 게시글은 복구하실 수 없습니다.")) {
			return false;
		}
		window.location.href = 'board_delete?code='+code+'&no='+no;
	}
	//Matthew End

	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on').find('ul li:nth-of-type(1)').addClass('active');
</script>
<div id="contents">
	<div class="main_tit">
		<h2 class="board-name">게시글 관리 <em><?=$board_info["name"]?></em></h2>
		
		<div class="btn_right">			
			<?php if($board_info["tree"] == "y" && $board_view["clevel"] == "0") : ?><a href="board_write?<?=$ref?>&cref=<?=$this->input->get("no", true)?>" class="btn point new_plus">+ 답글 쓰기</a><? endif ?>
			<!--2020-02-24 Inbet Matthew 삭제시 컨펌 기능 추가-->
			<!-- <a class="btn gray" href="board_delete?code=<?=$board_info["code"]?>&no=<?=$this->input->get("no", true)?>">삭제하기</a> -->
			<a class="btn gray sel_minus" href="javascript://" onclick="board_delete('<?=$board_info['code']?>', <?=$this->input->get('no', true)?>)">삭제</a>
			<!--Matthew End-->
			<a class="btn gray" href="board_list?<?=$ref?>">목록</a>
			<a class="btn point"href="board_write?<?=$ref?>">수정</a>
		</div><!--btn_right-->
	</div>
	<div class="sub_tit"><h3>게시글 내용</h3></div>
	<div class="table_write">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<colgroup>
				<col width="10%">
				<col width="23.3%">
				<col width="10%">
				<col width="23.3%">
				<col width="10%">
				<col width="*">
			</colgroup>
			<tr>
				<th class="ta_left">제목</th>
				<td colspan="5"><?php if(ib_isset($board_view['preface'])) :?>[<?=$board_view['preface']?>] <?php endif?><?=$board_view["title"]?></td>
			</tr>
			<tr>
				<th class="ta_left">아이디</th>
				<td><?=$board_view["userid"]?></td>
				<th class="ta_left">작성자</th>
				<td><?=$board_view["name"]?></td>
				<th class="ta_left">작성일</th>
				<td><?=$board_view["regdt"]?></td>
			</tr>
			<?php if($board_info["yn_email"] == "y" || $board_info["yn_mobile"] == "y") : ?>
				<tr>
					<?php if($board_info["yn_email"] == "y" && $board_info["yn_mobile"] == "y") : ?>
					<th class="ta_left">이메일</th>
					<td><?=$board_view["email"]?></td>
					<th class="ta_left">휴대폰</th>
					<td colspan="3"><?=$board_view["mobile"]?></td>
					<?php elseif($board_info["yn_email"] == "y" && $board_info["yn_mobile"] == "n") : ?>
					<th class="ta_left">이메일</th>
					<td colspan="5"><?=$board_view["email"]?></td>
					<?php elseif($board_info["yn_email"] == "n" && $board_info["yn_mobile"] == "y") : ?>
					<th class="ta_left">휴대폰</th>
					<td colspan="5"><?=$board_view["mobile"]?></td>
					<?php endif ?>
				</tr>
			<?php endif ?>
			<tr style="height:150px;">
				<th class="ta_left">내용</th>
				<td  colspan="5">
					<?php if ($board_info["yn_video"] == "y") : ?>
					<div class="view_video_wrap"><?=htmlspecialchars_decode($board_view["video_html"])?></div>
					<?php endif ?>
					<div class="yui3-cssreset">
					<?=htmlspecialchars_decode($board_view["content"])?>
					</div>
				</td>
			</tr>
            <?php if($board_info["thumbnail"] == 'y') : ?>
				<?php if(count($board_view["board_file"]["thumbnail"])) : ?>
				<tr>
					<th class="ta_left">썸네일</th>
					<td colspan="5">
						<?php for($i = 0; count($board_view["board_file"]["thumbnail"]) > $i; $i++) : ?><span class="thumb_link"><a href="/fileRequest/download?file=<?=urlencode("/board/". $board_view["upload_path"] ."/". $board_view["board_file"]["thumbnail"][$i]["fname"])?>&save=<?=urlencode($board_view["board_file"]["thumbnail"][$i]["oname"])?>" target="_blank" download><?=$board_view["board_file"]["thumbnail"][$i]["oname"]?> (다운로드)</a></span><?php endfor ?>
					</td>
				</tr>
				<?php endif ?>
            <?php endif ?>
			<?php if($board_info["files"] == 'y') : ?>
				<?php if(count($board_view["board_file"]["file"])) : ?>
				<tr>
					<th class="ta_left">첨부파일</th>
					<td colspan="5">
						<?php for($i = 0; count($board_view["board_file"]["file"]) > $i; $i++) : ?><span class="thumb_link"><a href="/fileRequest/download?file=<?=urlencode("/board/". $board_view["upload_path"] ."/". $board_view["board_file"]["file"][$i]["fname"])?>&save=<?=urlencode($board_view["board_file"]["file"][$i]["oname"])?>" target="_blank" download><?=$board_view["board_file"]["file"][$i]["oname"]?> (다운로드)</a></span><?php endfor ?>
					</td>
				</tr>
				<?php endif ?>
            <?php endif ?>
			<!--추가필드-->
            <!--답글은 추가필드 사용 x 2020-06-25-->
			<? if($board_info["extraFl"] == "y" && !empty($board_info["extraFieldInfo"]["use"][$board_view["language"]]) && $board_view['clevel'] == 0){ ?>
				<?
				foreach($board_info["extraFieldInfo"]["use"][$board_view["language"]] as $columnKey => $columnVal) {
					$boardOption = $board_info["extraFieldInfo"]["option"][$board_view["language"]][$columnKey];
                    
                    /*
                    if(empty($board_view["extraFieldInfo"][$board_view["language"]][$columnKey]) && is_numeric($board_view["extraFieldInfo"][$board_view["language"]][$columnKey]) === false){
                        continue;
                    }
                    */
				?>
					<tr>
						<th class="ta_left"><?=$board_info["extraFieldInfo"]["name"][$board_view["language"]][$columnKey]?></th>
						<td colspan="5">
							<?
							$htmlStr = "";
							switch($boardOption["type"]){
								case "file":
                                     if(empty($board_view["extraFieldInfo"][$board_view["language"]][$columnKey]) === false){
                                        $downloadPath = urlencode("/board/" . $board_view["upload_path"]. "/" . $board_view["extraFieldInfo"][$board_view["language"]][$columnKey]);
                                        if($boardOption["file_type"] == 'image') {
                                            $htmlStr .= "<img src='/fileRequest/download?file=".$downloadPath."' alt='".$board_view["extraFieldInfo"][$board_view["language"]][$columnKey."_oname"]."' style='width:".$boardOption["width"]."px;height:".$boardOption["height"]."px'/>";
                                        }elseif($boardOption["file_type"] == 'video'){
                                            $htmlStr .= "<video id='' src='/fileRequest/download?file=".$downloadPath."' controls width='100%'></video>";
                                        }else{
                                            $htmlStr .= "<span class='file_down_span'>";
                                            $htmlStr .= "<a href ='/fileRequest/download?file=" . $downloadPath . "&save=".urlencode($board_view["extraFieldInfo"][$board_view["language"]][$columnKey."_oname"])."' target='_blank' download>";
                                            $htmlStr .= $board_view["extraFieldInfo"][$board_view["language"]][$columnKey."_oname"];
                                            $htmlStr .= "</a>";
                                            $htmlStr .= "</span>";
                                        }
                                      }
									break;
								case "editor":
									$htmlStr .= '<div class="yui3-cssreset">';
									$htmlStr .= htmlspecialchars_decode($board_view["extraFieldInfo"][$board_view["language"]][$columnKey]);
									$htmlStr .= '</div>';
									break;
								default:
									$htmlStr .= htmlspecialchars_decode($board_view["extraFieldInfo"][$board_view["language"]][$columnKey]);
									break;
							}
							echo $htmlStr;
							?>
						</td>
					</tr>
				<? } ?>
			<? } ?>
			<?php
			if($board_info['code'] === 'gallery') :
				echo '<tr><th>태그</th><td colspan="5"><div id="tag">';
				if(count($tags) > 0) :
					$temp = [];
					foreach($tags as $key => $value) :
						$temp[] = '<p class="tags">#'.$value['tag'].'</p>';
					endforeach;
					echo implode('', $temp);
				endif;
				echo '</div></td></tr>';
			endif;
			?>
		</table>
	</div>
	<?php if($board_info["QNA_BOARD"] == $board_info["board_type"])  : ?>
	<div class="sub_tit"><h3>답변 내용</h3>
		<div class="btn_right">
			<?php if($board_info["QNA_BOARD"] == $board_info["board_type"]) : ?>
				<? if($board_view["answer_status"] == "y"){ ?>
					<a href="board_inquire_answer_delete?code=<?=$board_info["code"]?>&no=<?=$this->input->get("no", true)?>" class="btn gray sel_minus">
						답변 삭제
					</a>
				<? } ?>
				<!-- a href="board_write?code=<?=$board_info["code"]?>&no=<?=$this->input->get("no", true)?>&answer_status=<?=$board_view["answer_status"]?>" class="btn black" -->
				<a href="board_write?<?=$ref?>&no=<?=$this->input->get("no", true)?>&answer_status=<?=$board_view["answer_status"]?>" class="btn black">
					<?=$board_view["answer_status"] == "y" ? "답변 수정" : "답변 등록"?>
				</a>
			<? endif ?>
		</div><!--btn_right-->
	</div>
	<div class="table_write">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<colgroup>
				<col width="10%">
				<col width="23.3%">
				<col width="10%">
				<col width="23.3%">
				<col width="10%">
				<col width="*">
			</colgroup>
			<tr>
				<th class="ta_left">답변 제목</th>
				<td colspan="3"><?=$board_view["answer_title"]?></td>
				<th class="ta_left">처리 상태</th>
				<td><?=$board_view["answer_status"] == "y" ? "완료" : "대기"?></td>
			</tr>
			<tr>
				<th class="ta_left">아이디</th>
				<td><?=$board_view["answer_userid"]?></td>
				<th class="ta_left">작성자</th>
				<td><?=$board_view["answer_name"]?></td>
				<th class="ta_left">답변 작성일</th>
				<td><?=$board_view["answer_regdt"]?></td>
			</tr>
			<tr style="height:150px;">
				<th class="ta_left">내용</th>
				<td  colspan="5" class="view_cont"><?=htmlspecialchars_decode($board_view["answer_content"])?></td>
			</tr>
		</table>
	</div>
	<?php endif?>

	<?php if($board_info["comment"] == "y") :?>
		<div class="sub_tit"><h3><?=$this->_admin_member["name"]?> 댓글달기</h3></div>
		<?=form_open("", array("name" => "comment_frm"))?>
		<div class="bbs_admin_comment">
			<div class="comment_write">
				<input type="hidden" name="name" value="<?=$this->_admin_member["name"]?>" />
				<textarea name="content"></textarea>
				<input type="hidden" name="file_fname" />
				<input type="hidden" name="file_oname" />
				<a class="btn black" href="javascript://" onclick="Common_Board.board_comment_write(document.comment_frm);" >댓글달기</a>
			</div>
			<div class="comment_list">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<colgroup>
						<col width="*">
						<col width="15%">
					</colgroup>
					<tbody>
						<?php if(isset($board_list_comment)) : ?>
							<?php foreach($board_list_comment as $key => $value) : ?>
								<tr id="display_comment_<?=$value["idx"]?>">
									<td>
										<input type="hidden" name="idx_<?=$value["idx"]?>" value="<?=$value["idx"]?>" />
										<input type="hidden" name="mode_<?=$value["idx"]?>" value="" />
										<div id="view_comment_content_<?=$value["idx"]?>"><?=htmlspecialchars_decode($value["content"])?></div>
										<div id="modify_comment_content_<?=$value["idx"]?>" class="com_modify hide">
											<textarea name="content_<?=$value["idx"]?>" ><?=htmlspecialchars_decode($value["content"])?></textarea>
											<input type="hidden" name="name_<?=$value["idx"]?>" value="<?=$value["name"]?>" />
											<input type="hidden" name="file_fname_<?=$value["idx"]?>" />
											<input type="hidden" name="file_oname_<?=$value["idx"]?>" />
										</div>
									</td>
									<td align="right">
										<div id="view_comment_btn_<?=$value["idx"]?>">
											<a href="javascript://" onclick="comment_modify_check('<?=$value["idx"]?>');">댓글 수정</a><a href="javascript://" onclick="Common_Board.board_comment_delete('<?=$value["idx"]?>');">댓글 삭제</a>
										</div>
										<div id="modify_comment_btn_<?=$value["idx"]?>" class="hide" >
											<a href="javascript://" onclick="Common_Board.board_comment_modify('<?=$value["idx"]?>')" class="btn_modify">댓글 수정</a><a href="javascript://" onclick="comment_modify_display('<?=$value["idx"]?>', false);">댓글 취소</a>
										</div>
									</td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
					</tbody>
				</table>
			</div>
		</div>
		<?=form_close()?>
	<?php endif?>
</div><!-- //contents END -->
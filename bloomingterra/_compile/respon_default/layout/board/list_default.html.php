<?php /* Template_ 2.2.8 2024/03/11 13:59:34 /home/bloomingterra/www/data/skin/respon_default/layout/board/list_default.html 000003480 */ ?>
<div class="bbs_num">총 게시물 <strong><?php echo $TPL_VAR["board_list"]['total_rows']?></strong>개</div>
<table class="bbs_list" summary="게시글 제목, 작성자, 작성일, 조회수, 게시글 내용 등..">
	<caption>게시글 내용</caption>
	<colgroup>
		<col width="7%">
		<col >
	</colgroup>
	<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">제목</th>
		</tr>
	</thead>
	<tbody>
<?php if(isset($TPL_VAR["board_list"]['notice_list'])){?>
<?php if(count($TPL_VAR["board_list"]['notice_list'])> 0){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_list"]['notice_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
		<tr class="board_notice">
			<td>공지</td>
			<td class="left">
				<div class="board_tit">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>"><?php }?>
						<div class="board_ico">
<?php if($TPL_V1["clevel"]>'0'){?><img src="/data/skin/respon_default/images/common/icon_re.gif" alt="답글"><?php }?>
<?php if($TPL_V1["display_read"]=='s'){?><img src="/data/skin/respon_default/images/common/icon_secret.gif" alt="비밀글"><?php }?>
<?php if(count($TPL_V1["board_file"]['file'])> 0){?><img src="/lib/images/icon_attach_file.png" alt="첨부파일"><?php }?>
						</div>
						<h3>
							<strong><?php if($TPL_V1["preface"]){?>[<?php echo $TPL_V1["preface"]?>]<?php }?> <?php echo $TPL_V1["title"]?> </strong>
<?php if($TPL_VAR["board_info"]['comment']=='y'){?>
<?php if($TPL_V1["comment"]>'0'){?>[<?php echo $TPL_V1["comment"]?>]<?php }?>
<?php }?>
						</h3>
					</a>
				</div>
			</td>
		</tr>
<?php }}?>
<?php }?>
<?php }?>
<?php if(isset($TPL_VAR["board_list"]['board_list'])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_list"]['board_list'])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
		<tr>
			<td><?php echo $TPL_VAR["board_list"]['total_rows']-$TPL_K1-$TPL_VAR["board_info"]['offset']?></td>
			<td class="left">
				<div class="board_tit">
<?php if($TPL_V1["is_read"]=='s'){?><a href="/board/board_secret?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>&page=view"><?php }else{?><a href="/board/board_view?code=<?php echo $TPL_V1["code"]?>&no=<?php echo $TPL_V1["no"]?>"><?php }?>
						<h3>
							<div class="board_ico">
<?php if($TPL_V1["clevel"]>'0'){?><img src="/data/skin/respon_default/images/common/icon_re.gif" alt="답글"><?php }?>
<?php if($TPL_V1["display_read"]=='s'){?><img src="/data/skin/respon_default/images/common/icon_secret.gif" alt="비밀글"><?php }?>
<?php if(count($TPL_V1["board_file"]['file'])> 0){?><img src="/lib/images/icon_attach_file.png" alt="첨부파일"><?php }?>
							</div>
							<strong><?php if($TPL_V1["preface"]){?>[<?php echo $TPL_V1["preface"]?>]<?php }?> <?php echo $TPL_V1["title"]?> </strong>
<?php if($TPL_VAR["board_info"]['comment']=='y'){?>
<?php if($TPL_V1["comment"]>'0'){?>[<?php echo $TPL_V1["comment"]?>]<?php }?>
<?php }?>
						</h3>
					</a>
				</div>
			</td>
		</tr>
<?php }}?>
<?php }else{?>
<?php if(count($TPL_VAR["board_list"]['notice_list'])< 1){?>
		<tr>
			<td colspan="2">게시글이 없습니다.</td>
		</tr>
<?php }?>
<?php }?>
	</tbody>
</table><!--board_list-->
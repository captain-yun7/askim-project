<style>
ul#tag li { display: inline-block; margin-right: 10px; position: relative; }
.remove {
	background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMjEiIHZpZXdCb3g9IjAgMCAyMSAyMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSIjRkZGIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0yLjU5Mi4wNDRsMTguMzY0IDE4LjM2NC0yLjU0OCAyLjU0OEwuMDQ0IDIuNTkyeiIvPjxwYXRoIGQ9Ik0wIDE4LjM2NEwxOC4zNjQgMGwyLjU0OCAyLjU0OEwyLjU0OCAyMC45MTJ6Ii8+PC9nPjwvc3ZnPg==");
	position: absolute;
	top: -8px;
	right: -5px;
	background-size: 5px;
	background-color: #ff0000;
	background-repeat: no-repeat;
	background-position: center;
	width: 11px;
	height: 11px;
	border-radius: 3px;
}
</style>
<script type="text/javascript" src="/lib/admin/js/admin_board.js"></script>
<script type="text/javascript" src="/lib/smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<script>
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on').find('ul li:nth-of-type(1)').addClass('active');
</script>

<div id="contents">
	<div class="main_tit">
		<h2 class="board-name">게시글 관리 <em><?=$board_info["name"]?></em></h2>
		<div class="btn_right btn_num2">
			<a class="btn gray" href="board_list?code=<?=$board_info["code"]?>">목록</a>
			<a class="btn point" href="javascript://" onclick="Common_Board.board_write(document.frm);">저장</a>
		</div><!--btn_right-->
	</div>
	<?=form_open("", $form_attribute)?>
		<input type="hidden" name="admin_page_flag" value="y">
		<input type="hidden" name="write_userid" value="<?=$board_view["userid"]?>" />
		<input type="hidden" name="code" value="<?=$board_info["code"]?>" />
		<input type="hidden" name="mode" value="<?=$board_info["mode"]?>" />
		<input type="hidden" name="no" value="<?=$this->input->get("no", true)?>" />
		<input type="hidden" name="cref" value="<?=$this->input->get("cref", true)?>" />
        <input type="hidden" name="upload_path" value="<?=$board_view["upload_path"]?>" />
		<input type="hidden" name="password" value="<?=$board_view["password"]?>" />
		<input type="hidden" name="ref" value="<?=$ref?>" style="border:0;font-size:0;">
		<?php if($board_info["mode"] == "answer"){ ?>
		<input type="hidden" name="language" value="<?=$board_view["language"]?>">
		<?php } else if(!$this->_site_language["multilingual"]) { ?>
		<input type="hidden" name="language" value="<?=$this->_site_language["default"]?>">
        <?php } ?>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="12%">
					<col width="38%">
					<col width="12%">
					<col width="*">
				</colgroup>
				<?php if(($board_info["mode"] == "write" || $board_info["mode"] == "modify") && $board_info["yn_preface"] == "y" || $this->_site_language["multilingual"] && $board_info["mode"] != "answer" ) :?>
				<tr>
					<?php if($this->_site_language["multilingual"] && $board_info["mode"] != "answer" ) :?>
					<th class="ta_left">게시판 언어 설정</th>
					<td colspan="<?php if(($board_info["mode"] == "write" || $board_info["mode"] == "modify") && $board_info["yn_preface"] == "y" ) :?><?php else :?>3<?php endif ?>">
						<?php if($this->_site_language["multilingual"]) : ?>
							<select name="language" id="language" onchange="changeLanguage()">
								<?php foreach($this->_site_language["set_language"] as $key => $value) :?>
									<option value="<?=$key?>" <?=$board_view["language"] == $key || (!$board_view["language"] && $this->_site_language['defalult'] == $key) ? "selected" : ""?>><?=$value?></option>
								<?php endforeach ?>
							</select>
						<?php else :?>
							<input type="hidden" name="language"  value="<?=$this->_site_language["default"]?>" />
						<?php endif ?>
					</td>
					<?php endif ?>
					<?php if(($board_info["mode"] == "write" || $board_info["mode"] == "modify") && $board_info["yn_preface"] == "y" ) :?>
                    
					<th class="ta_left">말머리 <span>(국문전용)</span></th>
					<td colspan="<?php if($this->_site_language["multilingual"] && $board_info["mode"] != "answer" ) :?><?php else :?>3<?php endif ?>">
						<div class="prefaceArea">
                        <select name="preface">
							<option value="">선택안함</option>
							<?php foreach(explode(",", $board_info["preface"]) as $key => $val) : ?>
								<option value="<?=$val?>" <? if($board_view["preface"] == $val) : ?>selected<? endif?>><?=$val?></option>
							<?php endforeach ?>
						</select>
                        </div>
                        <span class="prefaceAreaTip bbs_cuation"><em>말머리 기능은 국문에서만 지원합니다.</em></span>
					</td>
					<?php endif ?>
				</tr>
				<? endif ?>
				<tr>
					<th class="ta_left">작성자</th>
					<td>
						<? if($board_info["mode"] == "write" || $board_info["mode"] == "answer") : ?>
							<input type="text" name="name" value="<?=$this->_admin_member["name"]?>"/>
						<? else : ?>
							<input type="text" name="name" value="<?=$board_view["name"]?>"/>
						<? endif ?>
					</td>
					<th class="ta_left">고정글</th>
					<td>
						<input type="radio" name="fixed-radio" id="fixed-n" value="n" onchange="fixed_change(this.value);" <?php if($board_info["mode"] == "answer" || ($board_view["cref"] != $board_view["no"])) :?>disabled<?php endif; ?> <? if(!($board_view["fixed"] > "0")) : ?>checked<? endif?> /><label for="fixed-n">사용안함</label>
						<input type="radio" name="fixed-radio" id="fixed-y" value="y" onchange="fixed_change(this.value);" <?php if($board_info["mode"] == "answer" || ($board_view["cref"] != $board_view["no"])) :?>disabled<?php endif; ?> <? if($board_view["fixed"] > "0") : ?>checked<? endif?> /><label for="fixed-y">고정</label>
						<input type="text" autocomplete="off" name="fixed" value="<? if($board_view["fixed"] > "0") : ?><?=$board_view["fixed"]?><? endif?>" <? if(!($board_view["fixed"] > "0")) : ?>disabled<? endif?>/>
						(리스트상단 고정게시글 숫자입력)
                        <p class="prefaceAreaTip bbs_cuation"><em>고정글 기능은 답글에서 지원하지 않습니다.</em></p>
					</td>
				</tr>
				<?php if($board_info["yn_email"] == "y" || $board_info["yn_mobile"] == "y") :?>
				<tr>
					<?php if($board_info["yn_email"] == "y") : ?>
					<th class="ta_left">이메일</th>
					<td colspan="<?php if($board_info["yn_mobile"] == "y") : ?><?php else :?>3<?php endif ?>">
						<input type="text" name="email" value="<?=$board_view["email"]?>" placeholder="ex) help@help.com"/>
					</td>
					<?php endif ?>
					<?php if($board_info["yn_mobile"] == "y") : ?>
					<th class="ta_left">휴대폰</th>
					<td colspan="<?php if($board_info["yn_email"] == "y") : ?><?php else :?>3<?php endif ?>">
						<input type="text" name="mobile" value="<?=$board_view["mobile"]?>" placeholder="ex) 000-0000-0000" />
					</td>
					<?php endif ?>
				</tr>

				<? endif ?>
				<?php if($board_info["yn_video"] == "y") : ?>
					<tr>
						<th class="ta_left">동영상 URL</th>
						<td colspan="3"><input type="text" name="video_url" value="<?=$board_view["video_url"]?>" placeholder="동영상 url을 기입해주세요" /></td>
					</tr>
				<?php endif ?>
				<tr>
					<th class="ta_left">제목</th>
					<td colspan="3">
						<div id="secret_box">
							<? if($board_info["secret"] == "2") : ?>
								<label for="is_secret-y"><input type="checkbox" name="is_secret" id="is_secret-y" value="y" <? if($board_view["is_secret"] == "y") : ?>checked<? endif ?> />비밀글로 작성</label>
							<? elseif($board_info["secret"] == "1") : ?>
								<input type="hidden" name="is_secret" value="y">
							<? else : ?>
								<input type="hidden" name="is_secret" value="n">
							<? endif ?>
						</div>
						<input type="text" name="title" value="<?=$board_view["title"]?>" />
					</td>
				</tr>
				<!-- 썸네일 추가 -->
				<?php if($board_info["thumbnail"] == "y") : ?>
                    <?php for($i=1; $board_info["thumbnail_count"] >= $i; $i++) : ?>
					<tr>
						<?php if($i === 1) : ?> <th class="ta_left" rowspan="<?=$board_info["thumbnail_count"]?>">목록페이지 썸네일</th> <?php endif ?>
						<td colspan="3">
							<input type="file" name="thumbnail<?=$i?>" />
							<div class="check_box">
							<input type="checkbox" name="thumbnail<?=$i?>_image" class="thumbnail_image" value="<?=$board_view["board_file"]["thumbnail"][($i-1)]["fname"]?>" <?php if($board_view["thumbnail_image"] && $board_view["board_file"]["thumbnail"][($i-1)]["fname"] == $board_view["thumbnail_image"]) :?> checked <?php endif ?>
							<?php if($i === 1) : ?> checked<?php endif ?>
							onclick="thumbnail_image_choice('thumbnail<?=$i?>');"/>대표이미지 사용
							</div>
							<input type="hidden" name="thumbnail<?=$i?>_oname" value="<?=$board_view["board_file"]["thumbnail"][($i-1)]["oname"]?>" />
							<input type="hidden" name="thumbnail<?=$i?>_fname" value="<?=$board_view["board_file"]["thumbnail"][($i-1)]["fname"]?>" />
							<input type="hidden" name="thumbnail<?=$i?>_type" value="image" />
							<input type="hidden" name="thumbnail<?=$i?>_size" value="<?=$board_info["filesize"]?>" />
							<input type="hidden" name="thumbnail<?=$i?>_folder" value="<?=_UPLOAD?>/board/<?=$board_info["code"]?>" />
							<span id="thumbnail<?=$i?>_filezone">
								<a href="/fileRequest/download?file=<?=urlencode("/board/". $board_view["upload_path"] ."/". $board_view["board_file"]["thumbnail"][($i-1)]["fname"])?>" target="_blank"><?=$board_view["board_file"]["thumbnail"][($i-1)]["oname"]?></a>
								<? if(isset($board_view["board_file"]["thumbnail"][($i-1)]["oname"]) && $board_view["board_file"]["thumbnail"][($i-1)]["oname"]) : ?><a href="javascript://" onclick="uploadForm.uploadRemove('thumbnail<?=$i?>')" class="file_no"><img src="/lib/images/btn_close.gif"></a><? endif ?>
							</span>
						</td>
					</tr>
                    <?php endfor ?>
				<?php endif?>
                <!--// 썸네일 끝 -->
				<?php if($board_info["files"] == "y") : ?>
                    <?php for($i=1; $board_info["file_count"] >= $i; $i++) : ?>
					<tr>
						<?php if($i === 1) : ?> <th class="ta_left" rowspan="<?=$board_info["file_count"]?>">첨부파일</th> <?php endif ?>
						<td colspan="3">
							<input type="file" name="file<?=$i?>" />
							<input type="hidden" name="file<?=$i?>_oname" value="<?=$board_view["board_file"]["file"][($i-1)]["oname"]?>" />
							<input type="hidden" name="file<?=$i?>_fname" value="<?=$board_view["board_file"]["file"][($i-1)]["fname"]?>" />
							<input type="hidden" name="file<?=$i?>_type" value="all" />
							<input type="hidden" name="file<?=$i?>_size" value="<?=$board_info["filesize"]?>" />
							<input type="hidden" name="file<?=$i?>_folder" value="<?=_UPLOAD?>/board/<?=$board_info["code"]?>" />
							<span id="file<?=$i?>_filezone">
								<a href="/fileRequest/download?file=<?=urlencode("/board/". $board_view["upload_path"] ."/". $board_view["board_file"]["file"][($i-1)]["fname"])?>" target="_blank" ><?=$board_view["board_file"]["file"][($i-1)]["oname"]?></a>
								<? if(isset($board_view["board_file"]["file"][($i-1)]["oname"]) && $board_view["board_file"]["file"][($i-1)]["oname"]) : ?><a href="javascript://" onclick="uploadForm.uploadRemove('file<?=$i?>')" class="file_no"><img src="/lib/images/btn_close.gif"></a><? endif ?>
							</span>
						</td>
					</tr>
                    <?php endfor ?>
				<?php endif?>
				<tr>
					<th class="ta_left">내용</th>
					<td colspan="3">
						<textarea id="content" name="content" style="display:none;"><?=$board_view["content"]?></textarea>
					</td>
				</tr>
				<!--추가필드-->
				<?
				if($board_info["extraFl"] == "y"){
					$arrEditScript = array();
				?>
					<? foreach($board_info["extraFieldInfo"]["use"] as $languageKey => $extraFieldData){ ?>
						<?
						foreach($extraFieldData as $columnKey => $columnVal){
							$extraOption = $board_info["extraFieldInfo"]["option"][$languageKey][$columnKey];
						?>
							<tr class="<?=$languageKey?>_tr extra_tr">
								<th class="ta_left">
								<? if(!empty($board_info["extraFieldInfo"]["require"][$languageKey][$columnKey])){ ?>
									<em>*</em>
								<? } ?>
								<?=$board_info["extraFieldInfo"]["name"][$languageKey][$columnKey]?>
								</th>
								<td colspan="3">
									<?
									// @todo @James 수정 시 값 표시 작업 진행
									$htmlStr = "";
									switch($extraOption["type"]){
										case "checkbox":
										case "radio":
											foreach($extraOption["item"] as $itemNm => $itemVal){
												$checkFl = ($board_view["extraFieldInfo"][$languageKey][$columnKey] == $itemVal) ? "checked" : "";;
												$htmlStr .= "<input type = '".$extraOption["type"]."' id='".$columnKey."-".$languageKey."-".$itemNm."' name='".$columnKey."_".$languageKey."' value='".$itemVal."' ".$checkFl.">";
												$htmlStr .= "<label for='".$columnKey."-".$languageKey."-".$itemNm."'>".$itemVal."</label>";
											}
											break;
										case "select":
												$htmlStr .= "<select name = '".$columnKey."_".$languageKey."'>";
												foreach($extraOption["item"] as $itemNm => $itemVal){
													$selectFl = ($board_view["extraFieldInfo"][$languageKey][$columnKey] == $itemVal) ? "selected" : "";
													$htmlStr .= "<option value = '".$itemVal."' ".$selectFl." ".$selectFl.">".$itemVal."</option>";
												}
												$htmlStr .= "</select>";
											break;
										case "editor":
											$htmlStr .= "<div class = 'editor-box'>";
											$htmlStr .= "<textarea name = '".$columnKey."_".$languageKey."' id = '".$columnKey."_".$languageKey."' class = 'editor' >";
											$htmlStr .= $board_view["extraFieldInfo"][$languageKey][$columnKey]; // 내용
											$htmlStr .= "</textarea>";
											$htmlStr .= "</div>";
											$arrEditScript[$languageKey][] = "attachSmartEditor('".$columnKey."_".$languageKey."', 'board');";
											//$htmlStr .= "<script>attachSmartEditor('".$columnKey."_".$languageKey."', 'board');</script>";
											break;
										case "file":
                                            ?>
											<input type="file" name="<?=$columnKey?>_<?=$languageKey?>" />
											<input type="hidden" name="<?=$columnKey?>_<?=$languageKey?>_oname" value="<?=$board_view["extraFieldInfo"][$languageKey][$columnKey."_oname"]?>" />
											<input type="hidden" name="<?=$columnKey?>_<?=$languageKey?>_fname" value="<?=$board_view["extraFieldInfo"][$languageKey][$columnKey]?>" />
											<input type="hidden" name="<?=$columnKey?>_<?=$languageKey?>_type" value="<?=$extraOption["file_type"]?>" />
											<input type="hidden" name="<?=$columnKey?>_<?=$languageKey?>_size" value="<?=$board_info["extra_file_size"]?>" />
											<input type="hidden" name="<?=$columnKey?>_<?=$languageKey?>_folder" value="<?=_UPLOAD?>/board/<?=$board_info["code"]?>" />
											<span id="<?=$columnKey?>_<?=$languageKey?>_filezone">
												<? if(!empty($board_view["extraFieldInfo"][$languageKey][$columnKey])) { ?>
													<a href="/fileRequest/download?file=<?=urlencode("/board/". $board_view["upload_path"] ."/". $board_view["extraFieldInfo"][$languageKey][$columnKey])."&save=".$board_view["extraFieldInfo"][$languageKey][$columnKey."_oname"]?>" target="_blank" >
														<?=$board_view["extraFieldInfo"][$languageKey][$columnKey."_oname"]?>
													</a>
													<a href="javascript://" onclick="uploadForm.uploadRemove('<?=$columnKey?>_<?=$languageKey?>')" class="file_no">
														<img src="/lib/images/btn_close.gif">
													</a>
												<? } ?>
											</span>
											<?

											break;
										default:
											$htmlStr .= "<input type = 'text' name = '".$columnKey."_".$languageKey."' value = '".$board_view['extraFieldInfo'][$languageKey][$columnKey]."'>";
											break;
									}

									echo $htmlStr;
									?>
								</td>
							</tr>
						<? } ?>
					<? } ?>
				<? } ?>

				<?php
				if($board_info['code'] === 'gallery') :
				?>
				<tr>
					<th>태그</th>
					<td colspan="3">
						<ul id="tag">
							<?php
							if(count($tags) > 0) :
								foreach($tags as $v) :
									echo '<li><input type="text" name="rtags['.$v['no'].']" class="tags" value="'.$v['tag'].'"><button type="button" class="remove"></button></li>';
								endforeach;
							endif;
							?>
							<li><input type="text" class="edit" placeholder="입력 후 엔터"></li>
						</ul>
					</td>
				</tr>
				<?php
				endif;
				?>
			</table>
			<div class="sub_tit"><h3>개별 SEO 설정</h3></div>
			<div class="table_write">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="12%" />
						<col width="*" />
					</colgroup>
					<tbody>
						<tr>
							<th>개별 설정 사용</th>
							<td>
								<input type="radio" name="use_seo" id="useN" value="n" checked><label for="useN">아니오</label>
								<input type="radio" name="use_seo" id="useY" value="y"<?=$board_view['use_seo'] == 'y' ? ' checked' : ''?>><label for="useY">예</label>
							</td>
						</tr>
						<tr>
							<th>타이틀(Title)</th>
							<td><input type="text" name="seo_title" value="<?=$board_view['seo_title']?>" style="width: 100%;"></td>
						</tr>
						<tr>
							<th>작성자(Author)</th>
							<td><input type="text" name="seo_author" value="<?=$board_view['seo_author']?>" style="width: 100%;"></td>
						</tr>
						<tr>
							<th>설명(Description)</th>
							<td><input type="text" name="seo_description" value="<?=$board_view['seo_description']?>" style="width: 100%;"></td>
						</tr>
						<tr>
							<th>키워드(Keywords)</th>
							<td><input type="text" name="seo_keywords" value="<?=$board_view['seo_keywords']?>" style="width: 100%;"></td>
						</tr>
						<?php if($board_info['code'] == 'gallery') : ?>
						<tr>
							<th>관련 게시글 1<br><small style="color:#888;font-weight:normal;">글 번호(no)</small></th>
							<td>
								<input type="number" name="related_no1" value="<?=$board_view['related_no1']?>" min="1" placeholder="예: 82" style="width: 200px;">
								<span style="color:#888;font-size:12px;margin-left:8px;">같은 게시판의 다른 글 번호를 입력하세요. 비워두면 표시되지 않습니다.</span>
							</td>
						</tr>
						<tr>
							<th>관련 게시글 2<br><small style="color:#888;font-weight:normal;">글 번호(no)</small></th>
							<td>
								<input type="number" name="related_no2" value="<?=$board_view['related_no2']?>" min="1" placeholder="예: 81" style="width: 200px;">
							</td>
						</tr>
						<?php endif ?>
					</tbody>
				</table>
			</div>
			<div class="terms_privecy_box">
				<dl>
					<dt>- 썸네일은 어떻게 설정하나요? </dt>
					<dd>
						갤러리형 타입의 게시판에만 썸네일 이미지가 사용 가능합니다. <br>
						"목록페이지 썸네일" 첫번째 이미지가 썸네일로 등록되며 보기페이지에도 노출됩니다.<br>
						"목록페이지 썸네일"이 없고 "첨부파일"만 사용할 경우, "첨부파일"에 등록한 이미지 파일이 썸네일로 적용되며 보기페이지에 다운로드 링크로 노출됩니다.
					</dd>
				</dl>
			</div>
		</div>
	<?=form_close()?>
</div><!-- //contents END -->
<script>
	var site_language = "<?=$this->_site_language["default"]?>";
	var chkEdit = {};
	var Common_Board = new common_board({
		code : "<?=$board_info["code"]?>",
		no : "<?=$this->input->get("no", true)?>"
	});

	var reqStr = {
		"kor": "휴대폰을 입력해주세요.",
		"eng": "Please enter the mobile phone number.",
		"chn" : "请输入密码",
		"jpn" : "モバイルを 入力してください。"
	};

	var validStr = {
		"kor": "올바른 휴대폰을 입력해주세요. ex)000-0000-0000)",
		"eng": "Mobile is only available in numbers and Hyphen.",
		"chn" : "仅能输入数字和'-'密码",
		"jpn" : "モバイルは 数字と'-'のみ入力可能"
	};

	$(function() {

		changeLanguage();
		$("form[name='frm']").validate({
			rules : {
				language : {required : true},
				title : {required : true},
				name : {required : true},
				<?php if($board_info["yn_mobile"] == "y") : ?>
					mobile : {
						required : true,
						phoneValid: {
							depends: function() {
								return $("#language").val() == "kor" ? true : false;
							}
						},
						onlyNumHyphenValid: {
							depends: function() {
								return $("#language").val() == "kor" ? false : true;
							}
						}
					},
				<?php endif?>
				<?php if($board_info["yn_email"] == "y") : ?>email : {required : true, email : true},<?php endif?>
				<?php if($board_info["yn_video"] == "y") : ?>video_url : {required : true, regUrlType : true},<?php endif?>
				"fixed-radio" : {required : true},
				fixed : {required : {depends : function(){return $("[name='fixed-radio'][value='y']").is(":checked")}}, number : true},
				content : {editorRequired : {depends : function(){return !getSmartEditor("content")}}},
				file : {},
				<? if($board_info["extraFl"] == "y") { ?>
						<? foreach($this->_site_language["set_language"] as $languageKey => $languageVal) { ?>
							<? foreach($board_info["extraFieldInfo"]["use"][$languageKey] as $columnKey => $columnVal) { ?>
								<?=$columnKey?>_<?=$languageKey?> : {
									editorRequired : {
										depends : function() {
											if(site_language == "<?=$languageKey?>"){
												<? if(!empty($board_info['extraFieldInfo']['require'][$languageKey][$columnKey])) { ?>
													<? if($board_info['extraFieldInfo']['option'][$languageKey][$columnKey]['type'] == 'editor') { ?>
														return !getSmartEditor("<?=$columnKey?>_<?=$languageKey?>");
													<? }else if($board_info['extraFieldInfo']['option'][$languageKey][$columnKey]['type'] == "file") { ?>
														if($("[name=<?=$columnKey?>_<?=$languageKey?>_fname]").val() == "" || !$("[name=<?=$columnKey?>_<?=$languageKey?>_fname]").val()) {
															return true;
														}else {
															return false;
														}

													<? }else if($board_info['extraFieldInfo']['option'][$languageKey][$columnKey]['type'] == "select") { ?>
														if($("[name=<?=$columnKey?>_<?=$languageKey?>]").val() == "" || !$("[name=<?=$columnKey?>_<?=$languageKey?>]").val()) {
															return true;
														}else {
															return false;
														}
													<? }else if($board_info['extraFieldInfo']['option'][$languageKey][$columnKey]['type'] == "checkbox" || $board_info['extraFieldInfo']['option'][$languageKey][$columnKey]['type'] == "radio") { ?>
														if($("[name=<?=$columnKey?>_<?=$languageKey?>]:checked").val() == "" || !$("[name=<?=$columnKey?>_<?=$languageKey?>]:checked").val()) {
															return true;
														}else {
															return false;
														}
													<? }else { ?>
														if($("[name=<?=$columnKey?>_<?=$languageKey?>]").val() == "" || !$("[name=<?=$columnKey?>_<?=$languageKey?>]").val()) {
															return true;
														}else {
															return false;
														}
													<? } ?>

												<? } else { ?>
                                                    <? if($board_info['extraFieldInfo']['option'][$languageKey][$columnKey]['type'] == 'editor') { ?>
														return (getSmartEditor("<?=$columnKey?>_<?=$languageKey?>") == undefined);
													<? } ?>
                                                <? } ?>

											}else {
												return false;
											}
										}
									}
								},
							<? } ?>
						<? } ?>
				<?} ?>
			}, messages : {
				language : {required : "언어를 선택해주세요."},
				title : {required : "제목을 입력해주세요."},
				<?php if($board_info["yn_mobile"] == "y") : ?>
					mobile : {
						required : "휴대폰 번호를 입력해 주세요.",
						phoneValid: function() {
							if($("#language").val() == "kor") return "올바른 휴대폰 번호를 입력해 주세요. ex)000-0000-0000)";
						},
						onlyNumHyphenValid: function() {
							if($("#language").val() != "kor") return "휴대폰 번호는 숫자와 하이픈(-)만 가능합니다.";
						}
					},
				<?php endif?>
				<?php if($board_info["yn_email"] == "y") : ?>email : {required : "이메일을 입력해주세요.", email : "올바른 이메일을 입력해주세요."},<?php endif?>
				<?php if($board_info["yn_video"] == "y") : ?>video_url : {required : "동영상 주소를 입력해주세요.", regUrlType : "올바른 url 주소가 아닙니다."},<?php endif?>
				name : {required : "작성자를 입력해주세요."},
				"fixed-radio" : {required : "고정글 여부를 선택해주세요."},
				fixed : {required : "고정 순서를 입력해주세요.", number : "숫자만 입력가능합니다."},
				content : {editorRequired : "내용을 입력해주세요."},
				file : {},
				<? if($this->_site_language["multilingual"]){ // 다국어 사용 o ?>
					<? foreach($this->_site_language["set_language"] as $languageKey => $languageVal) { ?>
						<? foreach($board_info["extraFieldInfo"]["use"][$languageKey] as $columnKey => $columnVal) { ?>
							<?=$columnKey?>_<?=$languageKey?> : {
								editorRequired : "<?=$board_info['extraFieldInfo']['name'][$languageKey][$columnKey]?>를 입력해주세요."
							},
						<? } ?>
					<? } ?>
				<? }else { // 다국어 사용 x ?>
					<? foreach($board_info["extraFieldInfo"]["use"]["kor"] as $columnKey => $columnVal) { ?>
						<?=$columnKey?>_<?=$languageKey?> : {
							editorRequired : "<?=$board_info['extraFieldInfo']['name']['kor'][$columnKey]?>를 입력해주세요."
						},
					<? } ?>
				<? } ?>
			}
		});

		attachSmartEditor("content", "board");
		uploadForm.init(document.frm);

		$(document).on("click", "button.remove", function() {
			if($(this).data("idx")) {
				if(confirm("삭제하시겠습니까?")) $(this).closest("li").remove();
			} else {
				$(this).closest("li").remove();
			}
		});

		$("input.edit").on("keydown", function(e) {
			if(e.keyCode === 32) return false;
			if(e.keyCode === 13) {
				e.preventDefault();
				var _this = $(this), tag = $(this).val(), fl = true;
				var li = "<li><input type='text' name='tags[]' class='tags' value='" + tag + "'><button type='button' class='remove'></button></li>";

				if(!tag) return false;

				$("input.tags").each(function() {
					if($(this).val() === tag) {
						alert("이미 등록된 태그입니다.");
						_this.val("");
						fl = false;
						return false;
					}
				});

				if(fl === true) {
					$(this).parent().before(li);
					$(this).val("");
				}
			}
		});
	});

	function fixed_change(value) {
		$("[name='fixed']").prop("disabled", !(value == "y"));
	}

    function thumbnail_image_choice(value) {
        var file_fname = $('[name="'+value+'_fname"]').val();

        if ($('[name="'+value+'_image"]').is(":checked") === true) {
            if (file_fname == "" || typeof file_fname === "undefined")
            {
                $('[name="'+value+'_image"]').prop("checked", false);
                alert("선택된 파일이 없습니다.");
                return false;
            } else {
                if ($(".thumbnail_image:checked").length > 1) {
                    $('[name="'+value+'_image"]').prop("checked", false);
                }else {
                    $('[name="'+value+'_image"]').prop("checked", true);
                    $('[name="'+value+'_image"]').val(file_fname);
                }
            }
        }
    }

	function changeLanguage() {
		var languageValue = $('[name="language"]').val();
		site_language = languageValue == undefined ? "kor" : languageValue;
		var preface = $('[name="preface"]');
		var prefaceArea = $('.prefaceArea');
		var prefaceAreaTip = $('.prefaceAreaTip');

		if (languageValue !== 'kor') {
			preface.prop('selectedIndex',0);
			prefaceArea.hide();
            prefaceAreaTip.show();
		} else {
			prefaceArea.show();
            prefaceAreaTip.hide();
		}

		$(".extra_tr").addClass("hide");
		$("."+ site_language +"_tr").removeClass("hide");
		<? foreach($arrEditScript as $languageKey => $arrScript){ ?>
			if(site_language == "<?=$languageKey?>" && !chkEdit[site_language]){
				chkEdit[site_language] = true;
				<?
					foreach($arrScript as $script){
						echo $script;
					}

					unset($arrEditScript[$languageKey]);
				?>
			}
		<? } ?>
	}
</script>
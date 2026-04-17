<?php /* Template_ 2.2.8 2025/02/27 09:54:01 /home/bloomingterra/www/data/skin/respon_default/board/_form_board_write.html 000022422 */ ?>
<!-- <script type="text/javascript" src="/lib/js/common_board.js"></script> -->
<script type="text/javascript" src="<?php echo $TPL_VAR["js"]?>/js/common_board.js"></script>
<script type="text/javascript" src="/lib/smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<script>
	var Common_Board = new common_board({
		code : "<?php echo $TPL_VAR["board_info"]['code']?>",
		no : "<?php echo $TPL_VAR["board_view"]['board_view']['no']?>",
		is_login : "<?php echo defined('_IS_LOGIN')?>"
	});

	$(function() {
		$("form[name='frm']").validate({
			rules : {
				title : {required : true},
<?php if($TPL_VAR["board_info"]['yn_mobile']=='y'){?>mobile : {required : true, phoneValid : true},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_email']=='y'){?>email : {required : true, email : true},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_video']=='y'){?>video_url : {required : true, regUrlType : true},<?php }?>
				name : {required : true},
<?php if($TPL_VAR["board_info"]['mode']!='modify'){?>
				password : {required : true, rangelength : [4, 20]},
<?php }?>
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'){?>//메인에서 에디터 적용금지
<?php if($TPL_VAR["board_info"]["yn_editor"]==="y"){?>
				content : {editorRequired : {depends : function(){return !getSmartEditor("contents")}}},
<?php }else{?>
				content: "required",
<?php }?>
<?php }?>
				file : {},
				nonMember : {required : {depends : function(){return <?php if(!defined('_IS_LOGIN')){?>true<?php }else{?>false<?php }?>}}},
				// 추가필드 rules Start
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
						<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?> : {
							editorRequired : {
								depends : function(){
<?php if(!empty($TPL_VAR["board_info"]['extraFieldInfo']['require'][$TPL_VAR["cfg_site"]['language']][$TPL_K1])){?>
<?php if($TPL_VAR["board_info"]['extraFieldInfo']['option'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]['type']=='editor'){?>
											return !getSmartEditor("<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>");
<?php }elseif($TPL_VAR["board_info"]['extraFieldInfo']['option'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]['type']=='file'){?>
											if(!$("[name=<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>_fname]").val()){
												return true;
											}
<?php }elseif($TPL_VAR["board_info"]['extraFieldInfo']['option'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]['type']=='checkbox'||$TPL_VAR["board_info"]['extraFieldInfo']['option'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]['type']=='radio'){?>
											if(!$("[name=<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>]:checked").val()){
												return true;
											}
<?php }else{?>
											if(!$("[name=<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>]").val()){
												return true;
											}
<?php }?>
										return false;
<?php }else{?>
<?php if($TPL_VAR["board_info"]['extraFieldInfo']['option'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]['type']=='editor'){?>
											getSmartEditor("<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>");
<?php }?>
										return false;
<?php }?>
								}
							}
						},
<?php }}?>
<?php }?>
				// 추가필드 rules End
<?php if($TPL_VAR["board_info"]["use_captcha"]=="y"){?>
				captcha: { required: true }
<?php }?>
			}, messages : {
				title : {required : "회사명을 입력해주세요."},
<?php if($TPL_VAR["board_info"]['yn_mobile']=='y'){?>mobile : {required : "연락처를 입력해주세요.", phoneValid : "올바른 연락처를 입력해주세요. ex)000-0000-0000)"},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_email']=='y'){?>email : {required : "이메일을 입력해주세요.", email : "올바른 이메일을 입력해주세요."},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_video']=='y'){?>video_url : {required : "동영상 주소를 입력해주세요.", regUrlType : "올바른 url을 입력해주세요."},<?php }?>
				name : {required : "담당자를 입력해주세요."},
<?php if($TPL_VAR["board_info"]['mode']!='modify'){?>
				password : {required : "비밀번호를 입력해주세요.", rangelength: $.validator.format("비밀번호는 {0}~{1}자입니다.")},
<?php }?>
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'){?>//메인에서 에디터 적용금지
<?php if($TPL_VAR["board_info"]["yn_editor"]==="y"){?>
					content : {editorRequired : "내용을 입력해주세요."},
<?php }else{?>
					content: "내용을 입력해 주세요.",
<?php }?>
<?php }?>
				file : {},
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'){?>//메인에서 태그 가져오지 못하는 오류 수정
				nonMember : {required : "<?php echo $TPL_VAR["terms"]['nonMember']['title']?>를 체크해주세요."},
<?php }else{?>
				nonMember : {required : "비회원 개인정보 수집항목 동의를 체크해주세요."},
<?php }?>
				// 추가필드 messages Start
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
					<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?> : {
						editorRequired : "<?php echo $TPL_VAR["board_info"]['extraFieldInfo']['name'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>는 필수 항목입니다."
					},
<?php }}?>
<?php }?>
				// 추가필드 messages End
<?php if($TPL_VAR["board_info"]["use_captcha"]=="y"){?>
				captcha: { required: "자동등록방지 코드를 입력해 주세요." }
<?php }?>
			}
		});

<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'){?>//메인에서 에디터 적용금지
<?php if($TPL_VAR["board_info"]["yn_editor"]==="y"){?>attachSmartEditor("contents", "board");<?php }?>
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_VAR["board_info"]['extraFieldInfo']['option'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]['type']=='editor'){?>
						// 추가필드 에디터 적용
						attachSmartEditor("<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>", "board");
<?php }?>
<?php }}?>
<?php }?>
<?php }?>
		uploadForm.init(document.frm);
		$.ajax({
			url : "/captchaRequest/get", 
			datatype : "json",
			type : "POST",
			data : {"page" : "write"},
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							$("#captcha_box").html(result.captcha.image);
						} else {
							alert(result.error);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
		$("#refreshCode").on("click", function() {
			$.ajax({
				url : "/captchaRequest/get", 
				datatype : "json",
				type : "POST",
				data : {"page" : "write"},
				success : function(response, status, request){
					if(status == "success") {
						if(request.readyState == "4" && request.status == "200") {
							var result = JSON.parse(response);
							if(result.code) {
								$("#captcha_box").html(result.captcha.image);
							} else {
								alert(result.error);
							}
						}
					}
				}, error : function(request, status, error){
					alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		});
	});

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
</script>
	<form name="frm" id="frm" action="/board/board_write" target="ifr_processor" method="POST">
		<fieldset>
			<legend>게시글 작성</legend>
			<input type="hidden" name="write_userid" value="<?php echo $TPL_VAR["board_view"]['board_view']['userid']?>" />
			<input type="hidden" name="code" value="<?php echo $TPL_VAR["board_info"]['code']?>" />
			<input type="hidden" name="mode" value="<?php echo $TPL_VAR["board_info"]['mode']?>" />
			<input type="hidden" name="no" value="<?php echo $TPL_VAR["board_view"]['board_view']['no']?>" />
			<input type="hidden" name="cref" value="<?php echo $TPL_VAR["board_view"]['board_view']['cref']?>" />
			<input type="hidden" name="upload_path" value="<?php echo $TPL_VAR["board_view"]['board_view']['upload_path']?>" />
			<!-- 메인에서 게시글 작성시 사용하는 폼 -->
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]=='index_'){?>
			<div class="contact_wrap">
				<div class="title">
					<h3>CONTACT</h3>
					<p>블루밍테라는 공간과 사람을 연결하고 <br>가장 합리적인 솔루션을 제시합니다.</p>
				</div>
				<div class="table_box">
					<div class="bbs_table_wrap">
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex1"){?>
						<div>
							<dl>
								<dt class="req"><span>지역/공간명</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl>
<?php }?>
<?php }}?>
<?php }?>
						</div>
						<div class="line2">
							<dl>
								<dt class="req"><span>회사명</span></dt>
								<dd><input type="text" name="title" id="title" value="<?php echo $TPL_VAR["board_view"]['board_view']['title']?>" /><label for="title" class="dn">제목</label>	</dd>
							</dl>	
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex2"){?>
							<dl>
								<dt class="req"><span>직책</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl>
<?php }?>
<?php }}?>
<?php }?>
						</div>
						<div class="dn">
							<dl>
								<dt>비밀번호</dt>
								<dd><input type="password" name="password" id="password" value="<?php echo time()?>"/><label for="password" class="dn">게시글 비밀번호</label></dd>
							</dl>
						</div>
						<div class="line2">
							<dl>
								<dt class="req"><span>담당자명</span></dt>
								<dd><input type="text" name="name" id="name" value="<?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?><?php echo $TPL_VAR["member"]['name']?><?php }?><?php }else{?><?php echo $TPL_VAR["board_view"]['board_view']['name']?><?php }?>" <?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }elseif($TPL_VAR["board_info"]['mode']=='modify'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }?>/><label for="name" class="dn">작성자</label></dd>
							</dl>
							<dl>
								<dt class="req">연락처</dt>	
								<dd><input type="text" name="mobile" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['mobile']?>" /></dd>
							</dl>
						</div>
						<div>
							<dl>
								<dt class="req">이메일</dt>
								<dd><input type="text" name="email" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['email']?>" required /></dd>
							</dl>
						</div>
<?php if($TPL_VAR["board_info"]['files']=='y'){?>
<?php if(is_array($TPL_R1=range( 1,$TPL_VAR["board_info"]['file_count']))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
						<div>
							<dl>
								<dt><span>파일첨부</span></dt>
								<dd class="file_attach">
									<input type="text" class="file_name" readonly placeholder="첨부파일">
									<input type="file" name="file<?php echo $TPL_V1?>" id="file" class="file_btn dn"><label for="file" class="label_btn_file">파일첨부</label>
									<input type="hidden" name="file<?php echo $TPL_V1?>_oname" value="<?php echo $TPL_VAR["board_view"]['board_view']['board_file']['file'][($TPL_K1)]['oname']?>" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_fname" value="<?php echo $TPL_VAR["board_view"]['board_view']['board_file']['file'][($TPL_K1)]['fname']?>" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_type" value="all" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_size" value="<?php echo $TPL_VAR["board_info"]['filesize']?>" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_folder" value="<?php echo _UPLOAD?>/board/<?php echo $TPL_VAR["board_info"]['code']?>" />
								</dd>
							</dl>
						</div>
<?php }}?>
<?php }?>
						<div>
							<dl>
								<dt class="req">프로젝트 설명</dt>
								<dd>
									<div class="edit-box" style="width:100%;"><textarea name="content" id="contents" title="내용을 입력하세요."><?php echo $TPL_VAR["board_view"]['board_view']['content']?></textarea></div>
								</dd>
							</dl>
						</div>
					</div>
					<div class="table_btn_box">
						<div class="policy_cont">
							<div>
								<input type="checkbox" name="nonMember" id="checkbox-nonMember"  checked/>
								<label for="checkbox-nonMember">개인정보 수집 및 이용에 동의합니다.</label>
								<a href="/service/usepolicy" target="_blank">전체보기</a>
							</div>
						</div><!-- .policy_cont -->
						<button onclick="Common_Board.board_write(this.form); return false;" class="btn_send"><a href="javascript://" >문의하기</a></button>
					</div>
				</div>
			</div>
			
<?php }else{?>
			<!-- 게시글 작성 페이지에서 게시글 작성시 사용하는 폼 -->
			<div class="contact_wrap">
				<div class="title">
					<h3>CONTACT</h3>
					<p>블루밍테라는 브랜드와 고객에게 <br>특별한 경험을 선물합니다.</p>
				</div>
				<div class="table_box">
					<div class="bbs_table_wrap">
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex1"){?>
						<div>
							<dl>
								<dt class="req"><span>지역/공간명</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl>
<?php }?>
<?php }}?>
<?php }?>
						</div>
						<div class="line2">
							<dl>
								<dt class="req"><span>회사명</span></dt>
								<dd><input type="text" name="title" id="title" value="<?php echo $TPL_VAR["board_view"]['board_view']['title']?>" /><label for="title" class="dn">제목</label>	</dd>
							</dl>	
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex2"){?>
							<dl>
								<dt class="req"><span>직책</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl>
<?php }?>
<?php }}?>
<?php }?>
						</div>
						<div class="dn">
							<dl>
								<dt>비밀번호</dt>
								<dd><input type="password" name="password" id="password" value="<?php echo time()?>"/><label for="password" class="dn">게시글 비밀번호</label></dd>
							</dl>
						</div>
						<div class="line2">
							<dl>
								<dt class="req"><span>담당자명</span></dt>
								<dd><input type="text" name="name" id="name" value="<?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?><?php echo $TPL_VAR["member"]['name']?><?php }?><?php }else{?><?php echo $TPL_VAR["board_view"]['board_view']['name']?><?php }?>" <?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }elseif($TPL_VAR["board_info"]['mode']=='modify'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }?>/><label for="name" class="dn">작성자</label></dd>
							</dl>
							<dl>
								<dt class="req">연락처</dt>	
								<dd><input type="text" name="mobile" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['mobile']?>" /></dd>
							</dl>
						</div>
						<div>
							<dl>
								<dt class="req"><span>이메일</span></dt>
								<dd><input type="text" name="email" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['email']?>" required /></dd>
							</dl>
						</div>
<?php if($TPL_VAR["board_info"]['files']=='y'){?>
<?php if(is_array($TPL_R1=range( 1,$TPL_VAR["board_info"]['file_count']))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
						<div>
							<dl>
								<dt><span>파일첨부</span></dt>
								<dd class="file_attach">
									<input type="text" class="file_name" readonly placeholder="첨부파일">
									<input type="file" name="file<?php echo $TPL_V1?>" id="file" class="file_btn dn"><label for="file" class="label_btn_file">파일첨부</label>
									<input type="hidden" name="file<?php echo $TPL_V1?>_oname" value="<?php echo $TPL_VAR["board_view"]['board_view']['board_file']['file'][($TPL_K1)]['oname']?>" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_fname" value="<?php echo $TPL_VAR["board_view"]['board_view']['board_file']['file'][($TPL_K1)]['fname']?>" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_type" value="all" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_size" value="<?php echo $TPL_VAR["board_info"]['filesize']?>" />
									<input type="hidden" name="file<?php echo $TPL_V1?>_folder" value="<?php echo _UPLOAD?>/board/<?php echo $TPL_VAR["board_info"]['code']?>" />
								</dd>
							</dl>
						</div>
<?php }}?>
<?php }?>
						<div>
							<dl>
								<dt class="req"><span>프로젝트 설명</span></dt>
								<dd>
									<div class="edit-box" style="width:100%;"><textarea name="content" id="contents" title="내용을 입력하세요."><?php echo $TPL_VAR["board_view"]['board_view']['content']?></textarea></div>
								</dd>
							</dl>
						</div>
						<div>
							<dl class="board_capthca">
								<dd>
									<div class="capthca_wrap">
										<span id="captcha_box"><?php echo $TPL_VAR["captcha"]["image"]?></span>
										<span class="" id="refreshCode"><img src="/data/skin/respon_default/images/sub/icon_reset.svg" alt="icon_reset"></span>
									</div>
									<input type="text" name="captcha" id="captcha" class="input" placeholder="자동가입방지 문자입력">
									<label for="captcha" class="dn">자동가입방지 문자입력</label>
								</dd>
							</dl>
						</div>
					</div>
					<div class="table_btn_box">
						<div class="policy_cont">
							<div>
								<input type="checkbox" name="nonMember" id="checkbox-nonMember" checked/>
								<label for="checkbox-nonMember">개인정보 수집 및 이용에 동의합니다.</label>
								<a href="/service/usepolicy" target="_blank">전체보기</a>
							</div>
						</div><!-- .policy_cont -->
						<button onclick="Common_Board.board_write(this.form); return false;" class="btn_send"><a href="javascript://" ><span>문의하기</span></a></button>
					</div>
				</div>
			</div>
			
<?php if($TPL_VAR["board_info"]['code']=='inquiry'){?>
<?php }else{?>
			<div class="btn_wrap ta_center">
				<button onclick="Common_Board.board_write(this.form); return false;"><a href="javascript://" class="btn btn_point">확인</a></button>
				<a href="/board/board_list?code=<?php echo $TPL_VAR["board_info"]['code']?>" class="btn btn_basic">취소</a>
			</div><!--btn_center-->

<?php }?>

<?php }?>
		</fieldset>
	</form>

<script>
	$(document).ready(function(){
		$('.sub_board .file_btn').change(function(e){
			$(this).siblings(".file_name").val(e.target.files[0].name);
		});
	});
</script>
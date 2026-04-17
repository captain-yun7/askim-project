<?php /* Template_ 2.2.8 2026/04/08 10:57:17 /gcsd33_bloomingterra/www/data/skin/respon_default_en/board/_form_board_write.html 000023114 */ ?>
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
<?php if($TPL_VAR["board_info"]['yn_mobile']=='y'){?>mobile : {required : true, onlyNumHyphenValid : true},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_email']=='y'){?>email : {required : true, email : true},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_video']=='y'){?>video_url : {required : true, regUrlType : true},<?php }?>
				name : {required : true},
<?php if($TPL_VAR["board_info"]['mode']!='modify'){?>
				password : {required : true, rangelength : [4, 20]},
<?php }?>
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'&&$TPL_VAR["board_info"]['code']!='inquiry'){?>//메인에서 에디터 적용금지
				content : {editorRequired : {depends : function(){return !getSmartEditor("contents")}}},
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
				title : {required : "Please enter the title."},
<?php if($TPL_VAR["board_info"]['yn_mobile']=='y'){?>mobile : {required : "Please enter the mobile phone number.", onlyNumHyphenValid : "mobile is only available in numbers and Hyphen."},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_email']=='y'){?>email : {required : "Please enter your e-mail.", email : "Please enter a valid email."},<?php }?>
<?php if($TPL_VAR["board_info"]['yn_video']=='y'){?>video_url : {required : "Please enter the URL of the video.", regUrlType : "Please enter the correct url."},<?php }?>
				name : {required : "Please enter the author of the post."},
<?php if($TPL_VAR["board_info"]['mode']!='modify'){?>
				password : {required : "Please enter a password.", rangelength: $.validator.format("The password is between {0}~{1} characters.")},
<?php }?>
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'&&$TPL_VAR["board_info"]['code']!='inquiry'){?>//메인에서 에디터 적용금지
				content : {editorRequired : "Please enter your content."},
<?php }?>
				file : {},
<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'&&$TPL_VAR["board_info"]['code']!='inquiry'){?>//메인에서 태그 가져오지 못하는 오류 수정
				nonMember : {required : "please check <?php echo $TPL_VAR["terms"]['nonMember']['title']?>."},
<?php }else{?>
				nonMember : {required : "Please check the consent for nonmember personal data collection items."},
				
<?php }?>
				// 추가필드 messages Start
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
					<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?> : {
						editorRequired : "<?php echo $TPL_VAR["board_info"]['extraFieldInfo']['name'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?> is required."
					},
<?php }}?>
<?php }?>
				// 추가필드 messages End
<?php if($TPL_VAR["board_info"]["use_captcha"]=="y"){?>
				captcha: { required: "Please enter the automatic registration prevention code." }
<?php }?>
			}
		});

<?php if($TPL_VAR["CI"]->uri->rsegments[ 1]!='index_'&&$TPL_VAR["board_info"]['code']!='inquiry'){?>//메인에서 에디터 적용금지
		attachSmartEditor("contents", "board");
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
                alert("No files selected.");
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
					<p>Blooming Terra connects space and people, <br>providing the most reasonable solutions.</p>
				</div>
				<div class="table_box">
					<div class="bbs_table_wrap">
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex1"){?>
						<div>
							<dl>
								<dt class="req"><span>Area of Interest</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl>
<?php }?>
<?php }}?>
<?php }?>
						</div>
						<div class="">
							<dl>
								<dt class="req"><span>Company</span></dt>
								<dd><input type="text" name="title" id="title" value="<?php echo $TPL_VAR["board_view"]['board_view']['title']?>" /><label for="title" class="dn">제목</label>	</dd>
							</dl>	
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex2"){?>
							<!-- <dl>
								<dt class="req"><span>Company</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl> -->
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
								<dt class="req"><span>Full Name</span></dt>
								<dd><input type="text" name="name" id="name" value="<?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?><?php echo $TPL_VAR["member"]['name']?><?php }?><?php }else{?><?php echo $TPL_VAR["board_view"]['board_view']['name']?><?php }?>" <?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }elseif($TPL_VAR["board_info"]['mode']=='modify'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }?>/><label for="name" class="dn">작성자</label></dd>
							</dl>
							<dl>
								<dt class="req">Phone Number</dt>	
								<dd><input type="text" name="mobile" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['mobile']?>" /></dd>
							</dl>
						</div>
						<div>
							<dl>
								<dt class="req"><span>Email</span></dt>
								<dd><input type="text" name="email" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['email']?>" required /></dd>
							</dl>
						</div>
<?php if($TPL_VAR["board_info"]['files']=='y'){?>
<?php if(is_array($TPL_R1=range( 1,$TPL_VAR["board_info"]['file_count']))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
						<div>
							<dl>
								<dt><span>File</span></dt>
								<dd class="file_attach">
									<input type="text" class="file_name" readonly placeholder="File">
									<div class="file_close"><span></span><span></span></div>
									<input type="file" name="file<?php echo $TPL_V1?>" id="file" class="file_btn dn"><label for="file" class="label_btn_file">Upload</label>
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
								<dt class="req">Message</dt>
								<dd>
									<div class="edit-box" style="width:100%;"><textarea name="content" id="contents" title="내용을 입력하세요."><?php echo $TPL_VAR["board_view"]['board_view']['content']?></textarea></div>
								</dd>
							</dl>
						</div>
<?php if($TPL_VAR["board_info"]["use_captcha"]=="y"){?>
<?php if(!$TPL_VAR["board_view"]["board_view"]["no"]){?>
						<div>
							<dl class="board_capthca">
								<dd>
									<div class="capthca_wrap">
										<span id="captcha_box"><?php echo $TPL_VAR["captcha"]["image"]?></span>
										<span class="" id="refreshCode"><img src="/data/skin/respon_default_en/images/sub/icon_reset.svg" alt="icon_reset"></span>
									</div>
									<input type="text" name="captcha" id="captcha" class="input" placeholder="Please enter the captcha">
									<label for="captcha" class="dn">자동가입방지 문자입력</label>
								</dd>
							</dl>
						</div>
<?php }?>
<?php }?>
					</div>
					<div class="table_btn_box">
						<div class="policy_cont">
							<div>
								<input type="checkbox" name="nonMember" id="checkbox-nonMember"  checked/>
								<label for="checkbox-nonMember">I agree to the the Privacy Policy</label>
								<a href="/service/usepolicy" target="_blank">View</a>
							</div>
						</div><!-- .policy_cont -->
						<button onclick="Common_Board.board_write(this.form); return false;" class="btn_send"><a href="javascript://" >Send</a></button>
					</div>
				</div>
			</div>
<?php }else{?>
			<!-- 게시글 작성 페이지에서 게시글 작성시 사용하는 폼 -->

			<div class="contact_wrap">
				<div class="title">
					<h3>CONTACT</h3>
					<p>Blooming Terra connects space and people, <br>providing the most reasonable solutions.</p>
				</div>
				<div class="table_box">
					<div class="bbs_table_wrap">
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex1"){?>
						<div>
							<dl>
								<dt class="req"><span>Area of Interest</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl>
<?php }?>
<?php }}?>
<?php }?>
						</div>
						<div class="">
							<dl>
								<dt class="req"><span>Company</span></dt>
								<dd><input type="text" name="title" id="title" value="<?php echo $TPL_VAR["board_view"]['board_view']['title']?>" /><label for="title" class="dn">제목</label>	</dd>
							</dl>	
<?php if($TPL_VAR["board_info"]['extraFl']=='y'&&!empty($TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])){?>
<?php if(is_array($TPL_R1=$TPL_VAR["board_info"]['extraFieldInfo']['use'][$TPL_VAR["cfg_site"]['language']])&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
<?php if($TPL_K1=="ex2"){?>
							<!-- <dl>
								<dt class="req"><span>Company</span></dt>
								<dd><input type="text" name="<?php echo $TPL_K1?>_<?php echo $TPL_VAR["cfg_site"]['language']?>" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['extraFieldInfo'][$TPL_VAR["cfg_site"]['language']][$TPL_K1]?>" /></dd>
							</dl> -->
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
								<dt class="req"><span>Full Name</span></dt>
								<dd><input type="text" name="name" id="name" value="<?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?><?php echo $TPL_VAR["member"]['name']?><?php }?><?php }else{?><?php echo $TPL_VAR["board_view"]['board_view']['name']?><?php }?>" <?php if($TPL_VAR["board_info"]['mode']=='write'||$TPL_VAR["board_info"]['mode']=='answer'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }elseif($TPL_VAR["board_info"]['mode']=='modify'){?><?php if(defined('_IS_LOGIN')){?>readonly<?php }?><?php }?>/><label for="name" class="dn">작성자</label></dd>
							</dl>
							<dl>
								<dt class="req">Phone Number</dt>	
								<dd><input type="text" name="mobile" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['mobile']?>" /></dd>
							</dl>
						</div>
						<div>
							<dl>
								<dt class="req"><span>Email</span></dt>
								<dd><input type="text" name="email" class="input" value="<?php echo $TPL_VAR["board_view"]['board_view']['email']?>" required /></dd>
							</dl>
						</div>
<?php if($TPL_VAR["board_info"]['files']=='y'){?>
<?php if(is_array($TPL_R1=range( 1,$TPL_VAR["board_info"]['file_count']))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_K1=>$TPL_V1){?>
						<div>
							<dl>
								<dt><span>File</span></dt>
								<dd class="file_attach">
									<input type="text" class="file_name" readonly placeholder="File">
									<div class="file_close"><span></span><span></span></div>
									<input type="file" name="file<?php echo $TPL_V1?>" id="file" class="file_btn dn"><label for="file" class="label_btn_file">Upload</label>
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
								<dt class="req">Message</dt>
								<dd>
									<div class="edit-box" style="width:100%;"><textarea name="content" id="contents" title="내용을 입력하세요."><?php echo $TPL_VAR["board_view"]['board_view']['content']?></textarea></div>
								</dd>
							</dl>
						</div>
<?php if($TPL_VAR["board_info"]["use_captcha"]=="y"){?>
<?php if(!$TPL_VAR["board_view"]["board_view"]["no"]){?>
						<div>
							<dl class="board_capthca">
								<dd>
									<div class="capthca_wrap">
										<span id="captcha_box"><?php echo $TPL_VAR["captcha"]["image"]?></span>
										<span class="" id="refreshCode"><img src="/data/skin/respon_default_en/images/sub/icon_reset.svg" alt="icon_reset"></span>
									</div>
									<input type="text" name="captcha" id="captcha" class="input" placeholder="Please enter the captcha">
									<label for="captcha" class="dn">자동가입방지 문자입력</label>
								</dd>
							</dl>
						</div>
<?php }?>
<?php }?>
					</div>
					<div class="table_btn_box">
						<div class="policy_cont">
							<div>
								<input type="checkbox" name="nonMember" id="checkbox-nonMember"  checked/>
								<label for="checkbox-nonMember">I agree to the the Privacy Policy</label>
								<a href="/service/usepolicy" target="_blank">View</a>
							</div>
						</div><!-- .policy_cont -->
						<button onclick="Common_Board.board_write(this.form); return false;" class="btn_send"><a href="javascript://" ><span>Send</span></a></button>
					</div>
				</div>
			</div>
<?php if($TPL_VAR["board_info"]['code']=='inquiry'){?>
<?php }else{?>
			<div class="btn_wrap ta_center">
				<button onclick="Common_Board.board_write(this.form); return false;"><a href="javascript://" class="btn btn_point">OK</a></button>
				<a href="/board/board_list?code=<?php echo $TPL_VAR["board_info"]['code']?>" class="btn btn_basic">Cancel</a>
			</div><!--btn_center-->

<?php }?>
<?php }?>
		</fieldset>
	</form>

<script>
	$(document).ready(function(){
		$('.file_btn').change(function(e){
			$(this).siblings(".file_name").val(e.target.files[0].name);
		});
		
		$('.file_close').on('click', function() {
			$('.file_name').val('');
		});
	});
</script>
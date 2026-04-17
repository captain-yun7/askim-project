<link rel="stylesheet" type="text/css" href="/lib/css/slick.css">
<script src="/lib/js/slick.js"></script>
<script type="text/javascript" src="/lib/js/jquery.bxslider.min.js"></script>
<script>
	var confData = JSON.parse('<?=json_encode($conf); ?>');
	var formObj = {
		"kor" : "",
		"eng" : "",
		"chn" : "",
		"jpn" : "",
	}

	$(function() {
		uploadForm.init(document.frm);
		$("select[name='conf[language]']").val("<?=$this->_site_language['default']?>");
		setForm(true);

		$("select[name='conf[language]'], input[name='conf[form]'], input[name='conf[type]']").on("change", setForm);

		// 미리보기
		$(".js-set-preview").on("change", setPreview);
	});

	function setForm(isFirst){
		var language = $("[name='conf[language]']").val();
		var type = $("input[name='conf[type]']:checked").val();

		var data = confData[type][language];

		if(isFirst === true){
			$("input[name='conf[form]'][value='" + data.form + "']").prop("checked", true);
		}else if($(this).prop("name") != "conf[form]"){
			if(formObj[language] != ""){
				$("input[name='conf[form]'][value='" + formObj[language] + "']").prop("checked", true);
			}else{
				$("input[name='conf[form]'][value='" + data.form + "']").prop("checked", true);
				formObj[language] = data.form;
			}
		}else{
			formObj[language] = $("input[name='conf[form]']:checked").val();
		}

		var form = $("input[name='conf[form]']:checked").val();

		if(form == 'responsive'){
			type = 'pc';
			data = confData[type][language];
		}



		// 타입 세팅
		$(".type").hide();
		$(".type."+form).show();
		if(!$(".type:visible :checked").length){
			$(".type:visible").eq(0).find(":radio").prop("checked", true);
		}


		if(form === 'fixed'){
			$("#fixedTitle").html(type === 'pc'? 'PC' : 'Mobile');
		}else{

		}

		// config 데이터 설정

		var dataLength = 0;
		if(typeof data == "undefined" || data == null) {
			dataLength = 0;
		}else {
			dataLength = Object.keys(data).length;
		}
		console.log(data);

		if(dataLength > 0){
			// fixed
			$("select[name='conf[fixed][time]']").val(data.fixed.time);
			$("select[name='conf[fixed][speed]']").val(data.fixed.speed);
			$(":radio[name='conf[fixed][mode]'][value='" + data.fixed.mode + "']").prop("checked", true);

			// responsive
			if(data.responsive){
				for(var i in data.responsive){
					var v = data.responsive[i];
					$("input[name='conf[responsive][" + i + "][width]']").val(v.width);
					$("select[name='conf[responsive][" + i + "][time]']").val(v.time);
					$("select[name='conf[responsive][" + i + "][speed]']").val(v.speed);
					$(":radio[name='conf[responsive][" + i + "][mode]'][value='" + v.mode + "']").prop("checked", true);
				}
			}

			$(".file_div").html(null);
			if(data.files){
				// fixed
				if(data.files.fixed && data.files.fixed.length > 0){
					for(var i=0; i<data.files.fixed.length; i++){
						addImage(data.files.fixed[i]);
					}
				}

				// responsive
				if(data.files.responsive && Object.keys(data.files.responsive).length > 0){
					for(var i in data.files.responsive){
						var v = data.files.responsive[i];
						for(var j=0; j<v.length; j++){
							addImage(v[j], i);
						}
					}
				}
			}
		} else {
			$("select[name*='[time]']").val(1);
			$("select[name*='[speed]']").val(100);
			$(":radio[name*='[mode]'][value='horizontal']").prop("checked", true);
			$(".file_div").html(null);
		}

		// 설정테이블 세팅
		$(".table_write.option").hide();
		$(".table_write.option."+form).show();

		setPreview();
	}

	function addImage(img, form) {
		if(form){
			var selector = '.file_div.' + form;
		} else {
			var selector = '.file_div.fixed';
		}
		var key = $(selector + " .file-div").last().data("key") === undefined? 0 : $(selector + " .file-div").last().data("key")+1;
		var formName = '[' + (form? 'responsive' : 'fixed') + ']' + (form? '[' + form + ']' : '');

		var image = {
			fname : '',
			oname : '',
			link : ''
		};
		if(typeof img === 'object'){
			image.fname = img["fname"];
			image.oname = img["oname"];
			image.link = img["link"];
		}

		var uploadUrl = getUploadUrl(form);

		var div = '';
		div += '<div class="file-div" data-key="' + key + '">';
		div += '	<input type="file" name="file' + formName + '[' + key + ']" />';
		div += '	<input type="hidden" name="oname_file' + formName + '[' + key + ']" value="' + image.oname + '" />';
		div += '	<input type="hidden" name="fname_file' + formName + '[' + key + ']" value="' + image.fname + '" />';
		div += '	<input type="hidden" name="type_file' + formName + '[' + key + ']" value="image" />';
		div += '	<input type="hidden" name="size_file' + formName + '[' + key + ']" value="10" />';
		div += '	<input type="hidden" name="folder_file' + formName + '[' + key + ']" value="" />';
		div += '	<span name="filezone_file' + formName + '[' + key + ']">';
		if(image.fname){
			div += '		<a href="' + uploadUrl + '/' + image.fname + '" target="_blank">' + image.oname + '</a>';
			div += '		<a href="javascript://" onclick="uploadForm.uploadRemove(\'file' + formName + '[' + key + ']\')" class="file_no"><img src="/lib/admin/images/btn_close.gif"></a>';
		}
		div += '	</span>';
		div += '	<button type="button" class="btn" onclick="delImage(this);">삭제</button>';
		div += '	<span class="link_span">';
		div += '		<input type="text" name="link_file' + formName + '[' + key + ']" value="' + image.link + '" placeholder="링크 입력" />';
		div += '	</span>';
		div += '</div>';

		$(selector).append(div);

		$(":file").off();
		uploadForm.init(document.frm);
	}

	function delImage(ele) {
		$(ele).parent().remove();
		setPreview();
	}

	function setPreview() {
		var formType = $("input[name='conf[form]']:checked").val();
		$(".table_write.option."+formType).each(function(){
			var form = $(this).data("form") || '';
			var li = [];

			$(this).find(".file_div .file-div").each(function(){
				var fname = $(this).find("input[name^='fname']").val();
				if(fname){
					var link = $(this).find("input[name^='link']").val();
					var uploadUrl = getUploadUrl(form);
					li.push('<li><a href="' + link + '"><img src="' + uploadUrl + '/' + fname + '" width="100%" height="731.98px"/></a></li>');
				}
			});

			var ul = '<ul class="visual_ul">' + li.join("\n") + '</ul>';
			$(this).find('.main_visual').html(ul);

			var mode	= $(this).find("[name*='[mode]']:checked").val();
			var speed	= $(this).find("[name*='[speed]']").val();
			var time	= $(this).find("[name*='[time]']").val() * 1000;
			$(this).find('.visual_ul').slick({
				fade: (mode === 'fade'? true : false),
				vertical: (mode === 'vertical'? true : false),
				autoplay: true,
				adaptiveHeight: true,
				speed: speed,
				autoplaySpeed: time,
				slickAdd: '<a href="">asdasdasdf</a>',
				slidesToShow : 1
			});
		});
	}

	function getUploadUrl(form){
		var imagePath = $("input[name='conf[type]']:checked").val() == 'pc'? 'imageSlide' : 'imageSlideMobile';
		var language = $("[name='conf[language]']").val();

		var uploadUrl = [];
		uploadUrl.push('<?=_UPLOAD?>');
		uploadUrl.push('main');
		uploadUrl.push(imagePath);
		uploadUrl.push(language);
		if(form){
			uploadUrl.push('responsive');
			uploadUrl.push(form);
		} else {
			uploadUrl.push('fixed');
		}

		return uploadUrl.join('/');
	}

	function removeUploadFile(){

	}

	/* CI UPLOAD */
	var uploadAction = "../../admin/FileRequest/upload";
	var uploadForm = {
		html5UploadPossible : window.FormData ? true : false,
		init : function (form) {
			this.upload({
				target : form.id,
				type : form.type ? form.type.value : "",
				folder : form.folder ? form.folder.value : ""
			}, function(result, ele) {
				if(!result.error){
					var elename = ele.name;
					var fname = '';
					var oname = '';
					var prevFname = $("[name='fname_"+ elename +"']").val();
					var folder = getUploadUrl($("[name='fname_"+ elename +"']").closest(".table_write").data("form"));
					var size = 0;
					var a = '';
					if(result.data){
						var node = result.data;
						fname = node.file_name;
						oname = node.client_name;
						size = node.file_size;
						a = '<a href="/fileRequest/download?file='+encodeURI(node.folder.replace("/upload", "")+"/"+fname)+'" target="_blank" >'+oname+'</a>';
						a += '<a href="javascript://" onclick="uploadForm.uploadRemove(\''+ elename +'\')" class="file_no"><img src="/lib/admin/images/btn_close.gif"></a>';
					}
					$("[name='"+ elename +"']").wrap('<form>').closest('form').get(0).reset();
					$("[name='"+ elename +"']").unwrap();
					$("[name='fname_"+ elename +"']").val(fname);
					$("[name='oname_"+ elename +"']").val(oname);
					$("[name='uploadsize_"+ elename +"']").val(size);
					$("[name='filezone_"+ elename +"'], #filezone_"+ elename).html(a);

					if(prevFname && folder && prevFname != ""){
						$.ajax({
							url : "../../admin/FileRequest/remove",
							method : 'post',
							data : {"folder" : folder, "fname" : prevFname},
							dataType : 'json',
							success : function(data){
								console.log(data);
								console.log("file remove success");
							},
							error : function(xhr, status, error){
								console.log("file remove error");
							}
						});
					}
				}else{
					alert(result.error);
				}
			});
		},
		uploadRemove : function(ele) {
			var folder = getUploadUrl($("[name='fname_"+ ele +"']").closest(".table_write").data("form"));
			var fname = $("[name='fname_"+ ele +"']").val();
			$("[name='fname_"+ ele +"']").val("");
			$("[name='oname_"+ ele +"']").val("");
			$("[name='filezone_"+ ele +"'], #filezone_"+ ele).html("");
			setPreview();

			if(folder && fname) {
				$.ajax({
					url : "../../admin/FileRequest/remove",
					method : 'post',
					data : {"folder" : folder, "fname" : fname},
					dataType : 'json',
					success : function(data){
						console.log(data);
						console.log("file remove success");
					},
					error : function(xhr, status, error){
						console.log("file remove error");
					}
				});
			}
		},

		/**
		 * [upload 비동기 파일 업로드]
		 * @param  {[object]}   option   [target : 타겟 인풋
		 *                             	type : 'image|video|document|excel'
		 *                             	size : '최대 사이즈 MB',
		 *                             	pixel : image일 경우 {width : 가로 사이즈, height: 세로 사이즈}
		 *                             	]
		 * @param  {Function} callback [후처리 함수]
		 */
		upload : function(option, callback) {
			if(option.target){
				if(this.html5UploadPossible){
					$(':file', '#'+option.target).on('change',function(e){
						var target = this;
						var file = target && target.files && target.files[0];

						if($("[name='folder_"+target.name+"']").val()) {
							option.folder = $("[name='folder_"+target.name+"']").val();
						}
						if($("[name='type_"+target.name+"']").val()) {
							option.type = $("[name='type_"+target.name+"']").val();
						}

						if($("[name='size_"+target.name+"']").val()) {
							option.size = $("[name='size_"+target.name+"']").val();
						}

						if($("[name='pixel_"+target.name+"']").val()) {
							option.pixel = [];
							option.pixel[0] = $("[name='pixel_"+target.name+"']:eq(0)").val();
							option.pixel[1] = $("[name='pixel_"+target.name+"']:eq(1)").val();
						}

						// 타입 및 언어에 맞는 폴더로 업로드폴더 변경
						var form = $(target).parents(".table_write").data("form");
						var uploadUrl = getUploadUrl(form);
						option.folder = uploadUrl;

						var formData = new FormData();
						formData.append('file', file);
						formData.append('folder', option.folder);
						if(option.type){
							formData.append('type', option.type);
						}
						if(option.size){
							formData.append('size', option.size);
						}
						if(option.pixel){
							formData.append('width', option.pixel[0]);
							formData.append('height', option.pixel[1]);
						}
						$.ajax({
							url : uploadAction,
							method : 'post',
							data : formData,
							dataType : 'json',
							contentType : false,
							processData : false
						})
						.then(function(result){
							callback(result, target);
							setPreview();
						});
					});
				}else{
					$(':file', '#'+option.target).on('change',function(e){
						var target = this;
						var $input = $(target);
						var t = new Date().getTime();

						var jaTarget = 'JA_Form'+t;
						$input.wrap($('<form/>', {
								id : jaTarget,
								action : uploadAction,
								method : 'post',
								enctype : 'multipart/form-data',
								target : jaTarget
							}
						));

						if($("[name='folder_"+ target.name +"']").val()) {
							option.folder = $("[name='folder_"+target.name+"']").val();
						}
						if($("[name='type_"+ target.name +"']").val()) {
							option.type = $("[name='type_"+target.name+"']").val();
						}

						if($("[name='size_"+ target.name +"']").val()) {
							option.size = $("[name='size_"+target.name+"']").val();
						}

						if($("[name='pixel_"+ target.name +"']").val()) {
							option.pixel = [];
							option.pixel[0] = $("[name='pixel_"+target.name+"']:eq(0)").val();
							option.pixel[1] = $("[name='pixel_"+target.name+"']:eq(1)").val();
						}

						var $form = $('#'+jaTarget);
						$form.append(dummyInput('folder',option.folder, t));
						if(option.type){
							$form.append(dummyInput('type',option.type, t));
						}
						if(option.size){
							$form.append(dummyInput('size',option.size, t));
						}
						if(option.pixel){
							$form.append(dummyInput('width',option.pixel[0], t));
							$form.append(dummyInput('height',option.pixel[1], t));
						}

						var $iframe = $('<iframe/>',{name:jaTarget,style:'display:none;'}).appendTo('body');

						$iframe.load(function(){
							var doc = this.contentWindow ? this.contentWindow.document : (this.contentDocument ? this.contentDocument : this.document);
							var root = doc.documentElement ? doc.documentElement : doc.body;
							var result = root.textContent ? root.textContent : root.innerText;
							callback(JSON.parse(result), target);

							$input.unwrap($form);
							$('.JA_Dummy'+t).remove();
							$iframe.remove();
						});

						$form.submit();
					});
				}
			} else {
				console.log('upload parameter error');
			}
		}
	};
</script>
<div id="contents">
	<div class="main_tit">
		<h2>메인 슬라이드 설정</h2>
		<div class="btn_right">
			<a href="javascript://" onclick="document.frm.submit();" class="btn point">저장</a>
		</div>
	</div>
	<div class="table_write_info">* 동일 형식, 타입의 슬라이 컷에 등록되는 이미지는 각각 "가로x세로" 비율 및 크기가 같아야 합니다.</div>
	<div class="table_write_info">* 편집시, 아래 미리보기를 통해 효과 및 지속시간을 확인 후 저장하시기 바랍니다.</div>
	<?=form_open("", array("name" => "frm", "id" => "frm"));?>
		<input type="hidden" name="reg" value="register" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="110px">
					<col width="*">
				</colgroup>
				<tbody>
					<tr scope="row" class="<?=$this->_site_language["multilingual"]? "" : "hide"?>">
						<th>언어선택</th>
						<td >
							<?php if($this->_site_language["multilingual"]) : ?>
								<select name="conf[language]">
									<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
										<option value="<?=$key?>"><?=$value?></option>
									<?php endforeach ?>
								</select>
							<?php else :?>
								<input type="hidden" name="conf[language]"  value="<?=$this->_site_language["default"]?>" />
							<?php endif ?>
						</td>
					</tr>
					<tr>
						<th>형식</th>
						<td >
							<label class="mgr10"><input type="radio" name="conf[form]" class="js-set-preview" value="fixed" checked/> 일반형 홈페이지</label>
							<label class="mgr10"><input type="radio" name="conf[form]" class="js-set-preview" value="responsive"/> 반응형 홈페이지</label>
						</td>
					</tr>
					<tr>
						<th>타입</th>
						<td>
							<label class="mgr10 type fixed"><input type="radio" name="conf[type]" value="pc" checked/> PC 슬라이드</label>
							<label class="mgr10 type fixed"><input type="radio" name="conf[type]" value="mobile"/> 모바일 슬라이드</label>
							<label class="mgr10 type responsive"><input type="radio" name="conf[type]" value="pc"/> 반응형 슬라이드</label>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table_write option no_table_write fixed">
			<div class="sub_tit"><h3><em id="fixedTitle">PC</em> 슬라이드 설정</h3></div>
			<div class="table_write option fixed" data-form="" style="border-top:1px #ccc solid;">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="110px">
						<col width="450px">
						<col width="110px">
						<col width="*">
					</colgroup>
					<tbody>
						<tr>
							<th>전환 효과</th>
							<td colspan="3">
								<label class="mgr10"><input type="radio" name="conf[fixed][mode]" value="horizontal" class="js-set-preview fixed"/>슬라이드 (우측에서 좌측으로 이동)</label>
								<label class="mgr10"><input type="radio" name="conf[fixed][mode]" value="vertical"  class="js-set-preview fixed"/>버티컬 (아래에서 위로 이동)</label>
								<label class="mgr10"><input type="radio" name="conf[fixed][mode]" value="fade" class="js-set-preview fixed"/>페이드 (투명해지면서 변경)</label>
							</td>
						</tr>
						<tr>
							<th>지속시간</th>
							<td>
								<select name="conf[fixed][time]" class="js-set-preview fixed">
									<? for($i=1; $i<=20; $i++) echo '<option value="' . $i . '">' . $i . '초' . '</option>'; ?>
								</select>
							</td>
							<th>이동속도</th>
							<td>
								<select name="conf[fixed][speed]" class="js-set-preview fixed">
								<? for($i=1; $i<=30; $i++) echo '<option value="' . ($i*100) . '">' . ($i*100) . '</option>'; ?>
								</select> * 1000 단위가 1초입니다.
							</td>
						</tr>
						<tr>
							<th>이미지 첨부</th>
							<td colspan="3">
								<div class="file_div_wrap">
									<div class="file_div fixed"></div>
									<button type="button" class="file_div_btn" onclick="addImage();">슬라이드 추가하기</button>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="slide_td_view">
								<div class="main_visual"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="table_write option no_table_write responsive">
			<div class="sub_tit"><h3>PC 슬라이드 설정</h3></div>
			<div class="table_write option responsive mb20" data-form="pc" style="border-top:1px #ccc solid;">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="110px">
						<col width="263px">
						<col width="110px">
						<col width="263px">
						<col width="110px">
						<col width="*">
					</colgroup>
					<tbody>
						<tr>

						</tr>
						<tr>
							<th>전환효과</th>
							<td colspan="5">
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][pc][mode]" value="horizontal"/>슬라이드 (우측에서 좌측으로 이동)</label>
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][pc][mode]" value="vertical"/>버티컬 (아래에서 위로 이동)</label>
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][pc][mode]" value="fade"/>페이드 (투명해지면서 변경)</label>
							</td>
						</tr>
						<tr>
							<th>지속시간</th>
							<td>
								<select name="conf[responsive][pc][time]" class="js-set-preview responsive">
									<? for($i=1; $i<=15; $i++) echo '<option value="' . $i . '">' . $i . '초' . '</option>'; ?>
								</select>
							</td>
							<th>이동속도</th>
							<td>
								<select name="conf[responsive][pc][speed]" class="js-set-preview responsive">
								<? for($i=1; $i<=30; $i++) echo '<option value="' . ($i*100) . '">' . ($i*100) . '</option>'; ?>
								</select> * 1000 단위가 1초입니다.
							</td>
							<th>인식넓이</th>
							<td>
								width <input type="text" name="conf[responsive][pc][width]" value="" class="inq_w105"/> px 이상
							</td>
						</tr>
						<tr>
							<th>이미지 첨부</th>
							<td colspan="5">
								<div class="file_div_wrap">
									<div class="file_div pc"></div>
									<button type="button" class="file_div_btn" onclick="addImage(false, 'pc');">슬라이드 추가하기</button>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="6" class="slide_td_view">
								<div class="main_visual"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="sub_tit"><h3>태블릿 슬라이드 설정</h3></div>
			<div class="table_write option responsive mb20" data-form="tablet">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="110px">
						<col width="263px">
						<col width="110px">
						<col width="263px">
						<col width="110px">
						<col width="*">
					</colgroup>
					<tbody>
						<tr>
							<th>전환 효과</th>
							<td colspan="5">
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][tablet][mode]" value="horizontal"/>슬라이드 (우측에서 좌측으로 이동)</label>
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][tablet][mode]" value="vertical"/>버티컬 (아래에서 위로 이동)</label>
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][tablet][mode]" value="fade"/>페이드 (투명해지면서 변경)</label>
							</td>
						</tr>
						<tr>
							<th>지속시간</th>
							<td>
								<select name="conf[responsive][tablet][time]" class="js-set-preview responsive">
									<? for($i=1; $i<=15; $i++) echo '<option value="' . $i . '">' . $i . '초' . '</option>'; ?>
								</select>
							</td>
							<th>이동속도</th>
							<td>
								<select name="conf[responsive][tablet][speed]" class="js-set-preview responsive">
								<? for($i=1; $i<=30; $i++) echo '<option value="' . ($i*100) . '">' . ($i*100) . '</option>'; ?>
								</select> * 1000 단위가 1초입니다.
							</td>
							<th>인식넓이</th>
							<td>
								width <input type="text" name="conf[responsive][tablet][width]" value="" class="inq_w105"/> px 이상
							</td>
						</tr>
						<tr>
							<th>이미지 첨부</th>
							<td colspan="5">
								<div class="file_div_wrap">
									<div class="file_div tablet"></div>
									<button type="button" class="file_div_btn" onclick="addImage(false, 'tablet');">슬라이드 추가하기</button>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="6" class="slide_td_view">
								<div class="main_visual"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="sub_tit"><h3>모바일 슬라이드 설정</h3></div>
			<div class="table_write option responsive" data-form="mobile">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
						<col width="110px">
						<col width="263px">
						<col width="110px">
						<col width="*">
					</colgroup>
					<tbody>
						<tr>
							<th>전환 효과</th>
							<td colspan="3">
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][mobile][mode]" value="horizontal"/>슬라이드 (우측에서 좌측으로 이동)</label>
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][mobile][mode]" value="vertical"/>버티컬 (아래에서 위로 이동)</label>
								<label class="mgr10"><input type="radio" class="js-set-preview responsive" name="conf[responsive][mobile][mode]" value="fade"/>페이드 (투명해지면서 변경)</label>
							</td>
						</tr>
						<tr>
							<th>지속시간</th>
							<td>
								<select name="conf[responsive][mobile][time]" class="js-set-preview responsive">
									<? for($i=1; $i<=15; $i++) echo '<option value="' . $i . '">' . $i . '초' . '</option>'; ?>
								</select>
							</td>
							<th>이동속도</th>
							<td>
								<select name="conf[responsive][mobile][speed]" class="js-set-preview responsive">
								<? for($i=1; $i<=30; $i++) echo '<option value="' . ($i*100) . '">' . ($i*100) . '</option>'; ?>
								</select> * 1000 단위가 1초입니다.
							</td>
						</tr>
						<tr>
							<th>이미지 첨부</th>
							<td colspan="3">
								<div class="file_div_wrap">
									<div class="file_div mobile"></div>
									<button type="button" class="file_div_btn" onclick="addImage(false, 'mobile');">슬라이드 추가하기</button>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="slide_td_view">
								<div class="main_visual"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	<?=form_close();?>
</div>
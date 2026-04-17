<script>

var site_language = "<?=$this->_site_language['default']?>";

$(function(){
	language_change("<?=$this->_site_language['default']?>");
});

function language_change(language,obj) {
	site_language = language;

	if(obj){
		$(".lang_icon_tab").find("li").each(function(i,e){
		if($(e).hasClass("on")){
			$(e).removeClass("on");
			}
		});

		$(obj).closest("li").addClass("on");
		var select_language = $(obj).attr("data-language");
		$("[name='language']").val(select_language);
	}

	if(language){
		$("#divList").find("tr").hide();
		$("#divList").find(".list_tr_"+language).show();
	}
}

function term_submit(code,obj){
	var language = $("[name='language']").val();
	var url = "terms_reg?code="+code+"&language="+language;

	location.href = url;
}

$('#leftmenu >ul > li:nth-of-type(2)').addClass('on');
</script>
<div id="contents">
	<input type = "hidden" name = "language" value = "<?=$this->_site_language['default']?>">
	<div class="main_tit">
		<h2>약관 및 개인정보정책</h2>
		<div class="lang_icon_tab">
			<?php if($this->_site_language["multilingual"]) : ?>
			<ul>
			<?php
			foreach($this->_site_language["support_language"] as $key => $value) :
				foreach($this->_site_language['set_language'] as $k => $v) :
					if($k == $key) :
						$on = $this->_site_language['default'] == $key ? "on" : "";
						echo "<li class='".$on." lang_".$key."'><a onclick='javascript:language_change(\"".$key."\", this);' data-language='".$key."'>".$value."</a></li>";
					endif;
				endforeach;
			endforeach;
			?>
			</ul>
			<?php endif ?>
		</div>
	</div>
	<div class="table_write_info"><?php if($this->_site_language["multilingual"]) : ?><br/>* 다국어의 경우, 각 나라별 법령에 기준하여 법령에 위배되지 않게 사용하셔야 합니다. <?php endif ?></div>

	<div class="table_list">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<col width="50">
				<col width="*">
				<col width="100">
			</colgroup>
			<thead>
				<tr>
					<th>번호</th>
					<th>항목</th>
					<th>관리</th>
				</tr>
			</thead>
			<tbody id='divList'>
				<? foreach($terms as $key => $value) : ?>
						<? $cnt = 0; ?>
						<? foreach($value as $skey => $sval) : ?>
							<tr class = "list_tr_<?=$key?>">
								<td class="<?=$key?>"><?=(count($value) - $cnt)?></td>
								<td align="left" class="<?=$key?>"><?=$sval["title"]?></td>
								<td class="<?=$key?>"><a onclick="javascript:term_submit('<?=$skey?>',this);" class="btn_mini">관리</a></td>
							</tr>
							<? $cnt++; ?>
						<? endforeach ?>
						<!-- <td><?=count($terms) - $key?></td> -->
						<!-- <td><?=$value[key($value)]["title"]?></td> -->
						<!-- <td><a onclick="javascript:term_submit('<?=key($value)?>',this);" class="btn_mini">관리</a></td> -->
				<? endforeach ?>
			</tbody>
		</table>
	</div>
	<div class="terms_privecy_box">
		<dl>
			<dt>- "개인정보 수집 및 이용에 대한 안내" 와 "(비회원) 개인정보 수집항목 동의" 의 차이점</dt>
			<dd>
			위 두개의 항목은 서로 같은 내용이나, 홈페이지 상에서 사용되는 페이지 차이로 인해 서로 구분해 놓은 항목입니다.<br/>
			서로 같은 내용이 노출되면 되므로, "개인정보 수집 및 이용에 대한 안내" 내용을 그대로 "(비회원) 개인정보 수집항목 동의" 에 삽입해주세요.<br/><br/>
			</dd>
		</dl>
		<dl>
			<dt>- "이용약관" 과 "개인정보 수집 및 이용에 대한 안내" 등, 꼭 써야하나요?</dt>
			<dd>
			<em>홈페이지 상에서 회원가입 및 홈페이지를 이용하는 사용자의 개인정보(이메일, 휴대폰번호, 이름 등..)를 입력받는 경우</em> <em class="point">필수 항목</em>입니다.<br/>
			홈페이지 사용자의 개인정보를 입력 받음에도 불구하고, 위 세가지 항목을 홈페이지 사용자에게 제공하지 않을경우, <em class="point">관계법령에 의거하여 홈페이지 운영자가 처벌</em>받을 수 있습니다.<br/><br/>
			</dd>
		</dl>
	</div>
</div>
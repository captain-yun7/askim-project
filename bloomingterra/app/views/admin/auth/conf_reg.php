<script>
	$(function(){
		language_change("<?=$this->_site_language['default']?>");
	});

	var site_language = "<?=$this->_site_language['default']?>";

	//기준몰 이벤트 함수
	function set_standart_mall(language,obj) {
		//체크 시
		if($(obj).is(":checked")){
			var len = $("[name='standard_mall']:checked").length;

			if(len > 1){
				$current = $("[name='standard_mall']:checked").not(obj);
				$parent_div = $current.closest("div");
				var language_name = $parent_div.find("[name='language_name']");

				if(confirm("이미 "+language_name.val()+"를 기준으로 사용하고 계십니다. 정말 변경하시겠습니까?")){
					$("[name='standard_mall']").prop("checked",false);
					$(obj).prop("checked",true);
				}else{
					$(obj).prop("checked",false);
					return false;
				}
			}
		}
	}

	function language_change(language,obj) {
		site_language = language;
		<?php // 컬럼명 선택언어 토글셋팅 ?>
		$("[name*='conf").addClass("hide");
		$("[name*='conf["+ language +"]']").removeClass("hide");

		if(obj){
			$(".lang_icon_tab").find("li").each(function(i,e){
			if($(e).hasClass("on")){
				$(e).removeClass("on");
				}
			});

			$(obj).closest("li").addClass("on");
		}

		<?php // 옵션 선택언어 토글셋팅 ?>
		$("[class*='box_option").addClass("hide");
		$("[class*='box_option_"+ language +"']").removeClass("hide");
	}

	function fieldSave(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}
		var isSubmit = true;

		$(frm).find(":text:not('.disabled')").each(function() {
			if(this.value == "") {
				alert("입력하지 않는 곳이 있습니다.");
				this.focus();
				isSubmit = false;
				return false;
			}
		});

		/*todo 기준몰 아무것도 체크 안되있을떄의 action*/
		var len = $("[name='standard_mall']:checked").length;

		if(len <= 0){
			alert("기준몰을 체크해주세요.");
			return false;
		}

		if(isSubmit) {
			frm.submit();
		}
	}
	$(document).ready(function() {
		//$( '.standard_wrap' ).prependTo( '#contents' );
	});
</script>
<div id="contents">
	<div class="main_tit">
		<h2>기본 정보 설정</h2>
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
		<div class="btn_right">
			<a href="javascript://" onclick="fieldSave(document.frm);" class="btn point">저장</a>
		</div>
	</div>

	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<!-- 기준언어 체크 기능 추가 -->
		<div class="standard_wrap">
			<div class="sub_tit"><h3>기준 언어 설정</h3></div>
			<div class="table_write_info">* 다국어 사용 시, 관리자 페이지와 사용자 페이지의 기준 언어를 설정하는 메뉴입니다. 체크 후 저장 버튼을 눌러야 적용됩니다.</div>
			<div class="table_write_info">* 사용자 페이지는 접속된 국가의 언어를 우선순위로 체크합니다.  예를들어, 국문, 영어 중 영어를 기준으로 체크하여도 한국에서는 한국어를 사용하므로 국문이 노출됩니다.</div>
			<div class="standard_box">
				<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
				<div name = "conf[<?=$key?>]">
					<input type = "hidden" name = "language_name" value = "<?=$value?>">
					<input type="checkbox" class="" name="standard_mall" id="standard_mall_<?=$key?>" value="<?=$key?>" onclick = "javascript:set_standart_mall('<?=$key?>',this)" <?php if($cfg_site['standard_mall'] == $key){echo "checked";}?>/>
					<label class = "standard_msg" for="standard_mall_<?=$key?>"><strong><?=$value?></strong>를 기준으로 사용</label><br/>
				</div>
			<?php endforeach ?>
			</div>
		</div>
		<!-- 기준언어 체크 기능 추가 -->
		<div class="sub_tit"><h3>기본 정보 설정</h3></div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup><col width="16%"></colgroup>
				<thead class="dn">
					<tr>
						<th scope="col">컬럼명</th>
						<th scope="col">내용</th>
					</tr>
				</thead>
				<tbody id='divList'>
					<tr>
						<th align="left">
							타이틀 (Title)
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
								<input type="text" name="conf[<?=$key?>][title]" value="<?=$cfg_site[$key]["title"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site['title']}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							사이트명
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
								<input type="text" name="conf[<?=$key?>][nameKor]" value="<?=$cfg_site[$key]["nameKor"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.nameKor}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							사이트명(영문)
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][nameEng]" value="<?=$cfg_site[$key]["nameEng"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.nameEng}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							담당자 이메일
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][adminEmail]" value="<?=$cfg_site[$key]["adminEmail"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.adminEmail}</span>
						</td>
					</tr>
					<!--<tr>
						<td align="left">
							사이트주소
						</th>
						<td align="left">
							<input type="text" name="conf[siteUrl]" value="<?=$cfg_site[$key]["siteUrl"]?>" />
						</td>
					</tr>-->
					<tr>
						<th align="left">
							상호명
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][compName]" value="<?=$cfg_site[$key]["compName"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.compName}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							업종
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][item]" value="<?=$cfg_site[$key]["item"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.item}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							업태
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][service]" value="<?=$cfg_site[$key]["service"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.service}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							사업장 우편번호
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][zipcode]" value="<?=$cfg_site[$key]["zipcode"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.zipcode}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							사업장 주소
						</th>
						<td align="left" class="inq_address">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][address]" value="<?=$cfg_site[$key]["address"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.address}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							대표자명
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][ceoName]" value="<?=$cfg_site[$key]["ceoName"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.ceoName}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							사업자번호
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][compSerial]" value="<?=$cfg_site[$key]["compSerial"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.compSerial}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							통신판매업신고번호
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][retailBusiness]" value="<?=$cfg_site[$key]["retailBusiness"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.retailBusiness}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							개인정보보호 책임자
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][adminName]" value="<?=$cfg_site[$key]["adminName"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.adminName}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							전화번호
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][compPhone]" value="<?=$cfg_site[$key]["compPhone"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.compPhone}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							팩스번호
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][compFax]" value="<?=$cfg_site[$key]["compFax"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.compFax}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							SMS 발송번호
						</th>
						<td align="left">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][smsRecall]" value="<?=$cfg_site[$key]["smsRecall"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.smsRecall}</span>
						</td>
					</tr>
					<tr>
						<th align="left">
							운영시간
						</th>
						<td align="left" class="inq_address">
							<?php foreach($this->_site_language["support_language"] as $key => $val){ ?>
							<input type="text" name="conf[<?=$key?>][customerHour]" value="<?=$cfg_site[$key]["customerHour"]?>" />
							<?php } ?>
							<span class="tag_info">{cfg_site.customerHour}</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="sub_tit"><h3>메일(SMTP) 설정</h3></div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="16%">
					<col>
				</colgroup>
				<thead class="dn">
					<tr>
						<th scope="col">컬럼명</th>
						<th scope="col">내용</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>보내는 사람</th>
						<td><input type="text" name="sender" value="<?=$smtp->sender?>"></td>
					</tr>
					<tr>
						<th>보안 연결</th>
						<td>
							<select name="secure">
								<option value="ssl">SSL</option>
								<option value="tls"<?=$smtp->secure == 'tls' ? ' selected' : ''?>>TLS</option>
							</select>
							<input type="text" name="port" value="<?=$smtp->port ? $smtp->port : 465?>" size="5">
						</td>
					</tr>
					<tr>
						<th>호스트</th>
						<td><input type="text" name="host" value="<?=$smtp->host?>"></td>
					</tr>
					<tr>
						<th>아이디</th>
						<td><input type="text" name="userid" value="<?=$smtp->userid?>" size="30"></td>
					</tr>
					<tr>
						<th>패스워드</th>
						<td><input type="text" name="userpassword" value="<?=$smtp->userpassword?>" size="20"></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="sub_tit"><h3>인스타그램 토큰 설정</h3></div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="16%">
					<col>
				</colgroup>
				<thead class="dn">
					<tr>
						<th scope="col">컬럼명</th>
						<th scope="col">내용</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>사용 여부</th>
						<td>
							<select name="yn_use">
								<option value="n" selected>사용안함</option>
								<option value="y"<?=$instagram->yn_use == 'y' ? ' selected' : ''?>>사용</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>인스타그램 주소</th>
						<td><input type="text" name="instagram_url" value="<?=$instagram->url?>" size="50"></td>
					</tr>
					<tr>
						<th>갖고 올 피드 개수</th>
						<td>
							<select name="feed_limit">
							<?php
							for($i = 1; $i <= 10; $i++) :
								$selected = $instagram->feed_limit == $i ? ' selected' : '';
								echo "<option value='".$i."'".$selected.">".$i."</option>";
							endfor;
							?>
							</select>
						</td>
					</tr>
					<tr>
						<th>토큰</th>
						<td><input type="text" name="token" value="<?=$instagram->token?>" size="200"></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?=form_close();?>
	<div class="terms_privecy_box">
		<dl>
			<dt>- 네이트, 다음 메일 발송을 위해 각 포털사에 "화이트 리스트" 도메인 등록해주세요.</dt>
			<dd>
			네이트와 다음에 화이트리스트 도메인 등록이 안된 경우, 스팸메일로도 메일 발송이 아예 안될 수 있습니다.<br><br>
			<em>네이트 화이트리스트 등록 방법</em><br>
			1. 메일 발송 날짜 및 시간대: (해당 사이트에서 메일 발송 신청한 날짜, 시간대)<br>
			2. 발신 메일 주소: (일반적으로 웹 마스터 메일로 발송되며, 해당 사이트의 고객센터로 확인 요청 후 기재)<br>
			3. 수신 메일 주소<br>
			4. 메일 제목: (해당 사이트의 고객센터로 확인 요청 후 기재)<br>
			5. 발송 IP: (해당 사이트의 고객센터로 확인 요청 후 기재)<br>
			6. 메일 발송 주소로 리턴 메일 수신 여부 확인: (해당 사이트의 고객센터로 확인 요청 후 기재)<br>
			7. 수신 측 스팸 / 수신 거부 목록 확인 여부: (네이트 메일 → 환경설정 → 스팸클린 설정 → 수신차단 → 수신차단 목록에서 차단 여부 확인)<br>
			8. 스팸 메일함 / 청구서함 / 광고함 / SNS함 목록 확인 여부<br>
			9. 연락처 및 통화 가능한 시간대: 해당 내용 기재 후 문의 메일 보내면 됨.<br><br>
			</dd>
		</dl>
	</div>
</div>
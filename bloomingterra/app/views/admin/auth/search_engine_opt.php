<script>
	$(function() {
		uploadForm.init(document.frm);
	});

	function field_save(frm) {
		if(!confirm("저장하시겠습니까?")) {
			return false;
		}

		var input  = $("<input>", {
			type : "hidden",
			name : "conf[favicon]",
			value : $("[name='conf[favicon]_fname']").val()
		});
		$(":input[name*='conf[favicon']").remove();
		$(frm).append(input);

		var input  = $("<input>", {
			type : "hidden",
			name : "conf[snsImage]",
			value : $("[name='conf[snsImage]_fname']").val()
		});
		$(":input[name*='conf[snsImage']").remove();
		$(frm).append(input);

		var input  = $("<input>", {
			type : "hidden",
			name : "conf[sitemap]",
			value : $("[name='conf[sitemap]_fname']").val()
		});
		$(":input[name*='conf[sitemap']").remove();
		$(frm).append(input);

		frm.submit();
	}
	
	$('#leftmenu >ul > li:nth-of-type(2)').addClass('on');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>검색엔진 최적화(SEO)</h2>
		<div class="lang_icon_tab">
			<ul>
			<?php
			$current = $this->input->get('lang', true) ? $this->input->get('lang', true) : 'kor';
			$languages = ['kor' => '한국어', 'eng' => '영어', 'chn' => '중국어', 'jpn' => '일본어'];
			foreach($languages as $key => $val) :
				$curr = $key == $current ? ' on' : '';
				echo '<li class="lang_'.$key.$curr.'"><a href="?lang='.$key.'">'.$val.'</a>';
			endforeach;
			?>
			</ul>
		</div>
		<div class="btn_right">
			<a href="javascript://" onclick="field_save(document.frm);" class="btn point">저장</a>
		</div>
	</div>
	<?=form_open("", array("name" => "frm", "id" => "frm"))?>
		<input type="hidden" name="mode" value="<?=$language ? 'update' : 'insert'?>" />
		<input type="hidden" name="admin_page_flag" value="y">
		<input type="hidden" name="language" value="<?=$current?>">
		<div class="sub_tit">
			<h3>기본 메타태그 설정</h3><span>메타태그설정은 포털사이트 검색키워드 등으로 사용됩니다.</span>
		</div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="200" />
					<col />
				</colgroup>
				<tbody id='divList'>
					<tr>
						<th>파비콘 (Favicon)</th>
						<td align=left style="padding-left:10px">
							<input type="file" name="favicon"/>
							<input type="hidden" name="favicon_fname" value="<?=str_replace('/upload/conf/', '', $favicon)?>"/>
							<input type="hidden" name="favicon_type" value="favicon" />
							<input type="hidden" name="favicon_folder" value="/upload/conf" />
							<span name="favicon_filezone">
							<? if($favicon) : ?><a href="/fileRequest/download?file=<?=str_replace('/upload/conf/', '/conf/', $favicon)?>" style="color:cornflowerblue;"><?=str_replace('/upload/conf/', '', $favicon)?></a><? endif ?>
							</span>
							파일은 업로드 즉시 반영됩니다. (ico 확장자, 영문 파일명으로 업로드 해주세요.)
						</td>
					</tr>
					<tr>
						<th>메타태그 작성자(Author)</th>
						<td class="input_w100p"><input type="text" name="author" value="<?=$author?>" /></td>
					</tr>
					<tr>
						<th>메타태그 설명(Description)</th>
						<td class="input_w100p"><input type="text" name="description" value="<?=$description?>" /></td>
					</tr>
					<tr>
						<th>메타태그 키워드(Keywords)</th>
						<td class="input_w100p"><input type="text" name="keywords" value="<?=$keywords?>" /></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="sub_tit">
			<h3>오픈그래프/트위터 메타태그 기본설정</h3><span>오픈그래프는 페이스북, 트위터, 카카오톡 등에서 사이트 링크 공유시 보여지는 설정입니다.</span>
		</div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="200" />
					<col />
				</colgroup>
				<tbody id='divList'>
					<tr>
						<th>대표이미지</th>
						<td class="input_w100p">
							<input type="file" name="og"/>
							<input type="hidden" name="og_fname" value="<?=$og_image?>"/>
							<input type="hidden" name="og_type" value="snsImage" />
							<input type="hidden" name="og_folder" value="/upload/conf" />
							<span id="og_filezone">
							<?php if($og_image) : ?><a href="/fileRequest/download?file=<?=str_replace('/upload/conf/', '/conf/', $og_image)?>" style="color:cornflowerblue;"><?=str_replace('/upload/conf/', '', $og_image)?></a><?php endif ?>
							</span>
							<p class="bbs_txt">대표이미지는 1200*630px 사이즈로 제작해주시되, 세로 위아래 15px씩은 카카오톡과 트위터에서 잘려보이니 배경으로 제작해서 올려주세요.</p>
							<ul class="bbs_txt_sub">
								<li>- 2:1 비율 (1200*600) : 카카오톡, 트위터</li>
								<li>- 1.9:1 비율 (1200*630) : 페이스북</li>
								<li>- 1:1 비율 : 텔레그램</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th>대표제목 (og:title, twitter:title)</th>
						<td align=left class="input_w100p"><input type="text" name="og_title" value="<?=$og_title?>" /></td>
					</tr>
					<tr>
						<th>대표설명 (og:description, twitter:description)</th>
						<td align=left class="input_w100p"><input type="text" name="og_description" value="<?=$og_description?>" /></td>
					</tr>
				</tbody>
			</table>
			<div class="terms_privecy_box">
				<dl>
					<dt>오픈그래프 디버깅</dt>
					<dd>
					오픈그래프 정보는 특정 SNS 상에서 해당 URL을 공유한 적이 있다면, 해당 SNS 서비스에서 디버깅을 통해 캐시를 없애야 최신 정보가 반영됩니다.<br>
						<p>카카오톡 디버거 : <a href="https://developers.kakao.com/tool/debugger/sharing" target="_blank">https://developers.kakao.com/tool/debugger/sharing</a> (카카오톡 계정 로그인 후 사용 가능)<br>
							페이스북 디버거 : <a href="https://developers.facebook.com/docs/marketing-api/advantage-catalog-ads/debugging-tools" target="_blank">https://developers.facebook.com/docs/marketing-api/advantage-catalog-ads/debugging-tools</a> (페이스북 계정 로그인 후 사용 가능)<br>
							트위터 디버거 : <a href="https://cards-dev.twitter.com/validator" target="_blank">https://cards-dev.twitter.com/validator</a> (트위터 계정 로그인 후 사용 가능)</p>
					</dd>
				</dl>
			</div>
		</div>
		<div class="sub_tit">
			<h3>사이트맵 설정</h3><span>사이트맵은 포털사이트 검색봇 방문에 필요하며, 사이트맵 파일(xml)을 생성하여 구글, 네이버 등 각 사이트에 등록해야합니다.</span>
		</div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="200" />
					<col />
				</colgroup>
				<tbody id='divList'>
					<tr>
						<th>사이트맵 경로</th>
						<td align=left class="input_w100p">
							<input type="file" name="sitemap"/>
							<input type="hidden" name="sitemap_fname" value="<?=$cfg_site["seo"]["sitemap"]?>"/>
							<input type="hidden" name="sitemap_type" value="sitemap" />
							<input type="hidden" name="sitemap_folder" value="<?=_UPLOAD?>" />
							<span id="sitemap_filezone">
								<? if($cfg_site["seo"]["sitemap"]) : ?><a href="/fileRequest/download?file=<?=urlencode("/../". $cfg_site["seo"]["sitemap"])?>" target="_blank" style="color:cornflowerblue;"><?=$cfg_site["seo"]["sitemap"]?></a><? endif ?>
							</span>
							파일은 업로드 즉시 반영됩니다.
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?=form_close()?>
</div>
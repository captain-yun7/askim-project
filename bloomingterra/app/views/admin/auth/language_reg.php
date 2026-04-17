<script>

    $(function() {
        checked_multilingual("<?=$this->_site_language['multilingual']?>");
    });

	function field_save(frm) {
        if ($('input[name="language[multilingual]"]:checked').val() == true) {
            if ($('input[name="language[set_language][]"]:checked').length < 2) {
				alert("기본언어를 2개이상 선택해주시기 바랍니다.");
                return false;
            }
        }

		if(!confirm("저장하시겠습니까?")) {
			return false;
		}

		frm.submit();
	}

	function checked_multilingual(stat) {

        $('input[name="language[set_language][]"]').each(function() {
            if (stat == true) {
                this.disabled = false;
            } else {
                this.disabled = true;
                if (this.value == "kor") $(this).prop("checked", true);
                else { $(this).prop("checked", false); }
            }
        });
	}

</script>
<div id="contents">
	<?=form_open("", array("name" => "frm", "id" => "frm"))?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="main_tit">
			<h2>사용 언어설정</h2>
			<div class="btn_right">
				<a href="javascript://" onclick="field_save(document.frm);" class="btn point">저장</a>
			</div>
		</div>
		<div class="table_write_info">* 기준언어는 "홈페이지 정보설정" 메뉴에서 설정 가능합니다. </div>
		<div class="table_write_info">* app/config/cfg_siteLanguage.php 새로운 언어를 추가해야하며, app/config/language/ 해당언어 폴더를 생성 및 파일들을 생성하셔야합니다.</div>
		<div class="table_write_info">* app/config/cfg_siteLanguage.php 에 새로 등록한 언어는 app/config/cfg_skin.php에도 언어코드가 존재해야합니다. </div>
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="160" />
					<col />
				</colgroup>
				<tbody id='divList'>
					<tr>
						<th >다국어 사용</th>
						<td align=left>
							<input type="radio" name="language[multilingual]" onclick="checked_multilingual(this.value)" id="multilingual-1" value="1" <?=$this->_site_language["multilingual"] ? "checked" : ""?> /><label for="multilingual-1">사용</label>
							<input type="radio" name="language[multilingual]" onclick="checked_multilingual(this.value)" id="multilingual-2" value="0" <?=!$this->_site_language["multilingual"] ? "checked" : ""?> /><label for="multilingual-2">사용안함</label>
							<span class="bbs_cuation"><em>사용안함 체크시 한국어로 설정됩니다.</em></span>
						</td>
					</tr>
					<tr>
						<th >사용언어 설정</th>
						<td align=left>
						    <?php foreach($this->_site_language["support_language"] as $key => $value) :?>
							    <input type="checkbox" name="language[set_language][]" id="language_<?=$key?>" value="<?=$key?>" <?=$this->_site_language["set_language"][$key] ? "checked" : ""?> /><label for="language_<?=$key?>"><?=$value?></label>
                            <?php endforeach ?>
						</td>
					</tr>
					<!--tr>
						<th >
							지원하는 언어
						</th>
						<td align=left>
							<?php foreach($this->_site_language["support_language"] as $key => $value) :?>
								<p><?=$key?> : <?=$value?></p>
							<?php endforeach?>
						</td>
					</tr-->
				</tbody>
			</table>
		</div>
	<?=form_close()?>
</div>
<? include _DIR ."/cms/_header.php"; ?>
<script>
	$('#leftmenu >ul > li:nth-of-type(1)').addClass('on').find('ul li:nth-of-type(4)').addClass('active');
</script>
<style>
form[name='frm'] input[type='text'], form[name='frm'] input[type='password'] { border : 0 !important; }
</style>
<div id="contents">
	<div class="main_tit">
		<h2>휴면회원 상세정보</h2>
		<div class="btn_right">
			<a href="member_dormant_list" class="btn gray">목록</a>
		</div><!--btn_right-->
	</div>
	<form name="frm">
		<div class="table_write">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				<tbody id='divList'>
				<tr>	
					<th align="left"><?=$memberField["name"]["kor"]["userid"]?>(필수)</th>
					<td><input type="text" name="userid" <? if($this->input->get("userid", true)) : echo "readonly"; endif; ?> value="<? if(isset($member_view["userid"])) : ?><?=$member_view["userid"]?><? endif; ?>" readonly /></td>
					<th align="left"><?=$memberField["name"]["kor"]["name"]?>(필수)</th>
					<td><input type="text" name="name" value="<? if(isset($member_view["name"])) : ?><?=$member_view["name"]?><? endif; ?>" readonly /></td>
				</tr>
				<tr>	
					<th align="left"><?=$memberField["name"]["kor"]["password"]?></th>
					<td><input type="password" name="password" readonly /></td>
					<th align="left"><?=$memberField["name"]["kor"]["level"]?></th>
					<td>
						<select name="level">
							<? foreach($member_grade_list as $value) :?>	
								<option value="<?=$value["level"]?>" <? if(isset($member_view["level"]) && $member_view["level"] == $value["level"]) : ?>selected<? endif; ?> ><?=$value["gradenm"]?></option>
							<? endforeach; ?>
						</select>
					</td>
				</tr>
				<? $i = 0;?>
				<? foreach($memberField["name"]["kor"] as $key => $value) : ?>
					<? if(isset($memberField["use"][$key]) && $memberField["use"][$key] == "checked") : ?>
						<? if($i % 4 == 0) : ?><tr><? endif; ?>
							<th align="left"><?=$value?><? if(isset($memberField["require"][$key]) && $memberField["require"][$key] == "checked") : echo "(필수)"; endif; ?></th>
							<td class="input_mail_add">
								<? if(in_array($memberField["type"][$key], array("checkbox", "radio"))) :?>
									<? foreach($memberField["option"][$key]["item"]["kor"] as $subKey => $subValue) : ?>
										<input type="<?=$memberField["type"][$key]?>" id="<?=$key?>-<?=$subKey?>" name="<?=$key?>" value="<?=$subKey?>" <? if(isset($member_view[$key]) && $member_view[$key] == $subKey) : ?>checked<? endif; ?>>
										<label for="<?=$key?>-<?=$subKey?>"><?=$subValue?></label>
									<? endforeach; ?>
								<?/* elseif($memberField["type"][$key] == "email") :
									<input type="hidden" name="<?=$key?>" value="<? if(isset($member_view[$key])) : ?><?=$member_view[$key]?><? endif; ?>" /> 
									<input type="text" name="<?=$key?>_id" value="<? if(isset($member_view[$key])) : ?><?=explode("@",$member_view[$key])[0]?><? endif; ?>" onkeyup="Common_Member.duplicate_init(this.form.email_duplicate);" class="mail_id" /> @ 
									<input type="text" name="<?=$key?>_domain" value="<? if(isset($member_view[$key])) : ?><?=explode("@",$member_view[$key])[1]?><? endif; ?>" onchange="Common_Member.duplicate_init(this.form.email_duplicate);" class="mail_domain" />
									<select name="<?=$key?>_domain_select" onchange="domain_select_change('<?=$key?>'); Common_Member.duplicate_init(this.form.email_duplicate);">
										<? foreach($memberField["option"][$key]["item"] as $subKey => $subValue) : ?>
											<option value="<?=$subValue?>"><?=$subValue?></option>
										<? endforeach; ?>
									<select>
									*/?>
									<? if($key == "email") : ?>
										<input type="hidden" name="email_duplicate" <? if($this->input->get("userid", true)) : ?>value="y"<? endif ?> />
									<? endif; ?>
								<? elseif($memberField["type"][$key] == "select") :?>
									<select name="<?=$key?>">
										<option value=""></option>
										<? foreach($memberField["option"][$key]["item"]["kor"] as $subKey => $subValue) : ?>
											<option value="<?=$subKey?>" <? if(isset($member_view[$key]) && $member_view[$key] == $subKey) : ?>selected<? endif; ?>><?=$subValue?></option>
										<? endforeach; ?>
									<select>
								<? else : ?>
									<input type="text" name="<?=$key?>" value="<? if(isset($member_view[$key])) : ?><?=$member_view[$key]?><? endif; ?>" readonly />
								<? endif; ?>
							</td>
						<?if($i % 4 == 1 || $i == count($memberField["name"]["kor"]) - 1) : ?></tr><? endif; ?>
						<? $i++; ?>
					<? endif; ?>
				<? endforeach; ?>
			</tbody>
			</table>
		</div>
		<div class="btn_paging">
			<div class="paging"></div>
		</div><!--btn_paging-->
	</form>
</div>
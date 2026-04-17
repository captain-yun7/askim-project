<style>
	.sub_tit {}
	.sub_tit h3 {font-weight:600;color:#333;font-size: 23px;line-height:1em;text-align:center;display: block;margin: 30px 0;}
	select {border: 1px solid #ccc;width: 140px;margin: 0 5px;font-size: 12px;color: #333;padding: 5px;box-sizing: border-box;overflow-y: auto;}
	select option {min-height:1.8em;}
	.btn_wrap {margin: 30px 0 0;text-align:center;}
	.btn_wrap .btn {display:inline-block;margin:0 3px;font-size: 14px;color:#fff;font-weight:400;min-width:64px;width:auto;padding:0 8px;height: 34px;line-height: 32px;text-align:center;box-sizing:border-box;vertical-align:top;border:none;}
	.btn_wrap .btn.gray {background-color:#888;}
	.btn_wrap .btn.point {background-color:#c94141;}

</style>
<div class="sub_tit"><h3>카테고리 설정</h3></div>
<form method="post">
	<input type="hidden" name="goods" value="<?php echo $this->input->get("goods", true)?>">
	<select name="category" id="category1" size="10" onchange="select_category(this.value, 1, 'category_reg');" required></select>
	<select name="category" id="category2" size="10" onchange="select_category(this.value, 2, 'category_reg');">
		<option value="" disabled>==2차 카테고리==</option>
	</select>
	<select name="category" id="category3" size="10" onchange="select_category(this.value, 3, 'category_reg');">
		<option value="">==3차 카테고리==</option>
	</select>
	<select name="category" id="category4" size="10" onchange="select_category(this.value, 4, 'category_reg');">
		<option value="">==4차 카테고리==</option>
	</select>
	<select name="category" id="category5" size="10">
		<option value="">==5차 카테고리==</option>
	</select>
	<div class="btn_wrap">
		<button type="submit" class="btn point">일괄 변경</button>
		<button type="button" class="btn gray">취소</button>
	</div>
</form>
<script src="/lib/js/jquery-2.2.4.min.js"></script>
<script src="/lib/admin/js/admin_goods.js"></script>
<script>
select_category("", "1", 'category_reg');
</script>
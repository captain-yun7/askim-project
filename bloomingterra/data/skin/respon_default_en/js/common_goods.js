var common_goods = function(param) {
	var is_login = param.is_login;

	this.goods_search = function() {
		var frm = $("form[name='list_frm']");
		frm.submit();
	}
	this.goods_list_type = function(display_type) {
		var frm = $("form[name='list_frm']");
		$("[name='display_type']", frm).val(display_type);
		frm.submit();
	}
}
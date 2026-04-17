 var common_member = function(param) {
	var is_login = param.is_login;
	var userid = param.userid;

	this.captcha_refresh = function () {
		$.ajax({
			url : "/captchaRequest/get",
			datatype : "json",
			type : "POST",
			data : {"page" : "join"},
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
	}


	this.userid_duplicate_check = function (input) {
		if(!input) {
			return false;
		}

		if(!input.value) {
			alert("Please enter your ID");
			input.focus();
			return false;
		}

		if(!onlyNumEngValidate(input.value)) {
			alert("ID is only available in English and numbers");
			input.focus();
			return false;
		}

		if(!(input.value.length >= 4 && input.value.length <= 14)) {
			alert("ID is 4-14 characters");
			input.focus();
			return false;
		}

		$.ajax({
			url : "/member/userid_duplicate_check",
			datatype : "json",
			type : "POST",
			data : {"userid" : input.value},
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							if(result.use) {
								$("[name='userid_duplicate']").val("y");
							}
							alert(result.msg);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}

	this.email_duplicate_check = function (input) {
		if(!input) {
			return false;
		}

		if($("[name='"+ input.name +"_id']").length && $("[name='"+ input.name +"_domain']").length) {
			$("[name='"+ input.name +"']").val($("[name='"+ input.name +"_id']").val() +"@"+ $("[name='"+ input.name +"_domain']").val());
		}

		if(!input.value) {
			alert("Please enter your e-mail");
			input.focus();
			return false;
		}

		if(!emailValidate(input.value)) {
			alert("Please enter a valid email");
			input.focus();
			return false;
		}

		$.ajax({
			url : "/member/email_duplicate_check",
			datatype : "json",
			type : "POST",
			data : {"userid" :  userid || "", "email" : input.value},
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							if(result.use) {
								$("[name='email_duplicate']").val("y");
							}
							alert(result.msg);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
	}

	this.duplicate_init = function(duplicate_input, input) {
		duplicate_input.value = "";
		if(input) {
			input.value = "";
		}
	}

	this.dormant_check = function(data) {
		var ajaxReturn = false;
		$.ajax({
			url : "/member/dormant_check",
			datatype : "json",
			type : "POST",
			data : data,
			async : false,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							if(result.dormant) {
								if(confirm(result.msg)) {
									ajaxReturn = Common_Member.dormant_release(data);
								} else {
									alert("Nomal access granted after inactive account is reprovisioned");
								}
							} else {
								alert(result.msg);
							}
						} else {
							ajaxReturn = true;
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
		return ajaxReturn;
	}

	this.dormant_release = function(data) {
		var ajaxReturn = false;
		$.ajax({
			url : "/member/dormant_release",
			datatype : "json",
			type : "POST",
			data : data,
			async : false,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							ajaxReturn = true;
						} else {
							alert(result.error);
						}
					}
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
		return ajaxReturn;
	}
}
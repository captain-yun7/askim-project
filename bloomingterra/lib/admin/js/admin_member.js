 var common_member = function(param) {
	var is_login = param.is_login;
	var userid = param.userid;
	this.userid_duplicate_check = function (input, language) {
		if(!input) {
			return false;	
		}

		if(!input.value) {
			alert("아이디를 입력해주세요.");
			input.focus();
			return false;
		}

		if(!onlyNumEngValidate(input.value)) {
			alert("아이디는 영어, 숫자만 사용 가능합니다.");
			input.focus();
			return false;
		}

		if(!(input.value.length >= 4 && input.value.length <= 14)) {
			alert("아이디는 4~14자입니다.");
			input.focus();
			return false;
		}
		
		$.ajax({
			url : "/admin/member/userid_duplicate_check", 
			datatype : "json",
			type : "POST",
			data : {"userid" : input.value, "language" : language},
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							if(result.use) {
								$("[name='userid_duplicate_"+language+"']").val("y");
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

	// 다국어 기능 추가로 인해 기존함수를 손상시키지 않으면서 언어 키를 붙이기 위한 수정 20200427
	// params HTML_ELEMENT frm
	// params string language (eng,kor,jpn,chn)
	this.email_duplicate_check = function () {
		let input = arguments[0];
		let languageKey = arguments.length == 2 ? "_"+arguments[1] : "";

		if(!input) {
			return false;	
		}
		if($("[name='"+ input.name +"_id']").length && $("[name='"+ input.name +"_domain']").length) {
			$("[name='"+ input.name +"']").val($("[name='"+ input.name +"_id']").val() +"@"+ $("[name='"+ input.name +"_domain']").val());
		}

		if(!input.value) {
			alert("이메일을 입력해주세요.");
			input.focus();
			return false;
		}

		if(!emailValidate(input.value)) {
			alert("올바른 이메일을 입력해주세요.");
			input.focus();
			return false;
		}

		$.ajax({
			url : "/admin/member/email_duplicate_check", 
			datatype : "json",
			type : "POST",
			data : {"userid" :  userid || "", "email" : input.value},
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							if(result.use) {
								$("[name='email_duplicate"+languageKey+"']").val("y");
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

	/*
		@deprecated 다국어 기능 추가 이전 스크립트 20200427
		this.email_duplicate_check = function (input) {
			if(!input) {
				return false;	
			}
			if($("[name='"+ input.name +"_id']").length && $("[name='"+ input.name +"_domain']").length) {
				$("[name='"+ input.name +"']").val($("[name='"+ input.name +"_id']").val() +"@"+ $("[name='"+ input.name +"_domain']").val());
			}

			if(!input.value) {
				alert("이메일을 입력해주세요.");
				input.focus();
				return false;
			}

			if(!emailValidate(input.value)) {
				alert("올바른 이메일을 입력해주세요.");
				input.focus();
				return false;
			}

			$.ajax({
				url : "/admin/member/email_duplicate_check", 
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
	*/

	this.duplicate_init = function(duplicate_input, input) {
		duplicate_input.value = "";
		if(input) {
			input.value = "";
		}
	}
}
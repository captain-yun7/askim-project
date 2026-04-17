var common_board = function(param) {
	var code = param.code;
	var no = param.no;
	
	/*
	 * 글 작성
	 *
	 * @param 
	 */
	this.board_write = function(form) {
		if(!$(form).valid()){
			return false;
		}
		form.submit();
	}

	/*
	 * 댓글 작성
	 *
	 * @param 
	 */
	this.board_comment_write = function(form) {

		if(!$(form).valid()){
			return false;
		}

		var set_data = {
			mode : "write",
			code : code,
			no : no,
			name : $("[name='name']", form).val(),
			content : $("[name='content']", form).val(),
			file_fname : $("[name='file_fname']", form).val(),
			file_oname : $("[name='file_oname']", form).val()
		};
		
		$.ajax({
			url : "/admin/board/board_comment_write", 
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert(result.msg);
							location.reload();
						} else {
							alert(result.error);
						}
					} 
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
	}

	/*
	 * 댓글 삭제
	 *
	 * @param 
	 */
	this.board_comment_delete = function(idx) {
		if(!confirm("삭제하시겠습니까?")) {
			return false;
		}

		var display_comment = $("#display_comment_"+ idx);

		var set_data = {
			code : code,
			no : no,
			idx : idx
		};

		$.ajax({
			url : "/admin/board/board_comment_delete", 
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert(result.msg);
							location.reload();
						} else {
							alert(result.error);
						}
					} 
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
	}

	/*
	 * 댓글 수정
	 *
	 * @param 
	 */
	this.board_comment_modify = function(idx) {
		var display_comment = $("#display_comment_"+ idx);
		
		if(!$("[name='content_"+idx +"']", display_comment).val()) {
			alert("내용을 입력해주세요.");
			$("[name='content_"+idx +"']", display_comment).focus();
			return false;
		}

		var set_data = {
			mode : "modify",
			code : code,
			no : no,
			idx : idx,
			name : $("[name='name_"+ idx +"']", display_comment).val(),
			content : $("[name='content_"+ idx +"']", display_comment).val(),
			file_fname : $("[name='file_fname_"+ idx +"']", display_comment).val(),
			file_oname : $("[name='file_oname_"+ idx +"']", display_comment).val()
		};
		
		$.ajax({
			url : "/admin/board/board_comment_write", 
			datatype : "json",
			type : "POST",
			data : set_data,
			success : function(response, status, request){
				if(status == "success") {
					if(request.readyState == "4" && request.status == "200") {
						var result = JSON.parse(response);
						if(result.code) {
							alert(result.msg);
							location.reload();
						} else {
							alert(result.error);
						}
					} 
				}
			}, error : function(request, status, error){
				alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
			}
		});
	}
};
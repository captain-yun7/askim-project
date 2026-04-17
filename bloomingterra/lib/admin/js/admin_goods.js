function select_category(category, level, page) { // 셀렉트 카테고리 셋팅
	$("#category"+ level).html('<option value="">=='+ level +'차 카테고리==</option>');

	if(!$("#category"+ level).length) {
		return false;
	} 

	var set_data = {
		category : category,
		level : level
	};
	$.ajax({
		url : "/admin/goods/select_category", 
		datatype : "json",
		type : "POST",
		data : set_data,
		async : true,
		success : function(response, status, request){
			if(status == "success") {
				if(request.readyState == "4" && request.status == "200") {
					var result = JSON.parse(response);
					if(result.code && result.data) { 
						var str = '';
						var current_level_category_select = (category || "").substr(0, level * 3);
						
						//2020-03-05 Inbet Matthew 카테고리 선택시 자동으로 선택한 뎁스의 하위 뎁스의 최고 높은 순번 +1한 값을 input name sort에 넣어준다
						if(category){
							if (level == Number((category.length) / 3) +1){
								var sort = 0;
							}
						}else{
							if (level == 1){
								var sort = 0;
							}
						}
						// Matthew End

						for(var i = 0; i < result.data.length; i++) {
							var row = result.data[i];
							
							//2020-03-05 Inbet Matthew 카테고리 선택시 자동으로 선택한 뎁스의 하위 뎁스의 최고 높은 순번 +1한 값을 input name sort에 넣어준다
							if (sort != undefined){
								if (Number(row.sort) > Number(sort)){
									sort = Number(row.sort);
								}
							}
							// Matthew End

							str += '<option value="'+ row.category +'" '+ (row.category == current_level_category_select ? 'selected' : '') +'>'+ row.categorynm +'</option>';
						}	
						
						//2020-03-05 Inbet Matthew 카테고리 선택시 자동으로 선택한 뎁스의 하위 뎁스의 최고 높은 순번 +1한 값을 input name sort에 넣어준다
						if (sort != undefined && page == 'category_reg'){
							$('input[name="sort"]').val(Number(sort)+1);
						}
						// Matthew End

						$("#category"+ level).append(str);
					} 
				}
			}
		}, error : function(request, status, error){
			alert("code:"+ request.status +"\n"+"message:"+ request.responseText +"\n"+"error:"+ error);
		}
	});
	
	select_category(category, Number(level) + 1, page);
}
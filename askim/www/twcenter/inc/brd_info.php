<?
if(!empty($brand)){

	$position = "";
	$sql = "select * from wiz_brand where idx = '$brand'";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){

		if($row['subimg'] != ""){ $subimg = $row['subimg']; $subimg_type = $row['subimg_type']; }

		if(!strcmp($row['idx'],$brand)){

			if($row['brduse'] == "N") error("사용하지 않는 브랜드입니다.");

	   	$catname = $row['brdname'];
	   	$brd_info = $row;

		}

		$position = "&gt; <a href='$PHP_SELF?ptype=list&brand=".$row['idx']."'>".$row['brdname']."</a>";

		$cat_info['prd_width'] = $row['prd_width'];
		$cat_info['prd_height'] = $row['prd_height'];
		$cat_info['prd_num'] = $row['prd_num'];

	}

	if($subimg_type == "FIL"){

		$img_ext = substr($subimg,-3);

		if($img_ext == "swf"){
			$subimg = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\">";
			$subimg .= "<param name=\"movie\" value=\"/data/subimg/$subimg\">";
			$subimg .= "<param name=\"quality\" value=\"high\">";
			$subimg .= "<embed src=\"/twcenter/data/subimg/$subimg\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>";
			$subimg .= "</object>";
		}else{
			$subimg = "<img src='/twcenter/data/subimg/$subimg'>";
		}

	}

}
?>
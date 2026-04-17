<?php
	$imgpath = WIZHOME_PATH."/data/prdimg";		// 이미지 위치

	// 업로드 디렉토리 생성
	if(!is_dir($imgpath)) {
		$oldmask = umask(0);
		mkdir($imgpath, 0707);
		chmod($imgpath, 0707);
		umask($oldmask);
	}

	if(fileperms($imgpath) != 16837 && fileperms($imgpath) != 16839 && fileperms($imgpath) != 16895){
		error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
	}

	// 상품이미지명을 미리가져온다.
	$sql = "select * from wiz_product where prdcode = '$prdcode'";
	$row = sql_fetch_object($sql);

	// 상품이미지 자동저장
	if(is_uploaded_file($_FILES['realimg']['tmp_name'])) {

		$realimg['size']     = $_FILES['realimg']['size'];
		$realimg['name']     = $_FILES['realimg']['name'];
		$realimg['tmp_name'] = $_FILES['realimg']['tmp_name'];
		$realimg_name        = $prdcode."_tmp";

		if($realimg['size'] > 0){

			ImageResize_Upload_check($realimg['name']);

//			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $realimg['name']);
//			$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//			$realimg_ext = strtolower(substr($realimg['name'],$len));

//			$ext = getFileExt($realimg['name']);
			$realimg_ext = getFileExt($realimg['name']);

			copy($realimg['tmp_name'],$imgpath."/".$realimg_name);
			chmod($imgpath."/".$realimg_name, 0606);
			$prdimg_R_name = $prdcode."_R.".$realimg_ext;
			$prdimg_L1_name = $prdcode."_L1.".$realimg_ext;
			$prdimg_M1_name = $prdcode."_M1.".$realimg_ext;
			$prdimg_S1_name = $prdcode."_S1.".$realimg_ext;

			img_resize($realimg_name, $prdimg_R_name, $imgpath,  $oper_info['prdimg_R'], $oper_info['prdimg_R']);
			img_resize($realimg_name, $prdimg_L1_name, $imgpath, $oper_info['prdimg_L'], $oper_info['prdimg_L']);
			img_resize($realimg_name, $prdimg_M1_name, $imgpath, $oper_info['prdimg_M'], $oper_info['prdimg_M']);
			img_resize($realimg_name, $prdimg_S1_name, $imgpath, $oper_info['prdimg_S'], $oper_info['prdimg_S']);

			unlink($imgpath."/".$realimg_name);

		}

	}

	
	for($ii = 2; $ii <= $prdimg_max; $ii++) {
		
		if(!isset($_FILES['realimg'.$ii]['tmp_name'])) $_FILES['realimg'.$ii]['tmp_name'] = '';
		if(is_uploaded_file($_FILES['realimg'.$ii]['tmp_name'])) {

			${'realimg'.$ii.'_name'} = $prdcode."_tmp";

			if($_FILES['realimg'.$ii]['size'] > 0){

				ImageResize_Upload_check($_FILES['realimg'.$ii]['name']);

//				${'ext'.$ii} = preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['realimg'.$ii]['name']);
//				${'len'.$ii} = (strlen(${'ext'.$ii}) == 3) ? "-3" : "-4";
//
//				$realimg_ext = strtolower(substr($_FILES['realimg'.$ii]['name'],${'len'.$ii}));
				
				$realimg_ext = getFileExt($_FILES['realimg'.$ii]['name']);

				copy($_FILES['realimg'.$ii]['tmp_name'],$imgpath."/".${'realimg'.$ii.'_name'});
				chmod($imgpath."/".${'realimg'.$ii.'_name'}, 0606);
				${'prdimg_L'.$ii.'_name'} = $prdcode."_L".$ii.".".$realimg_ext;
				${'prdimg_M'.$ii.'_name'} = $prdcode."_M".$ii.".".$realimg_ext;
				${'prdimg_S'.$ii.'_name'} = $prdcode."_S".$ii.".".$realimg_ext;

				img_resize(${'realimg'.$ii.'_name'}, ${'prdimg_L'.$ii.'_name'}, $imgpath, $oper_info['prdimg_L'], $oper_info['prdimg_L']);
				img_resize(${'realimg'.$ii.'_name'}, ${'prdimg_M'.$ii.'_name'}, $imgpath, $oper_info['prdimg_M'], $oper_info['prdimg_M']);
				img_resize(${'realimg'.$ii.'_name'}, ${'prdimg_S'.$ii.'_name'}, $imgpath, $oper_info['prdimg_S'], $oper_info['prdimg_S']);

				unlink($imgpath."/".${'realimg'.$ii.'_name'});

			}

		}
	
	}


	// 상품이미지 개별저장
	if($_FILES['realimg']['size'] <= 0){

		if(is_uploaded_file($_FILES['prdimg_R']['tmp_name'])) {

			$prdimg_R['size']     = $_FILES['prdimg_R']['size'];
			$prdimg_R['name']     = $_FILES['prdimg_R']['name'];
			$prdimg_R['tmp_name'] = $_FILES['prdimg_R']['tmp_name'];

			if($prdimg_R['size'] > 0){

				ImageResize_Upload_check($prdimg_R['name']);
//				$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $prdimg_R['name']);
//				$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//				$prdimg_R_ext  = strtolower(substr($prdimg_R['name'],$len));
				
				$prdimg_R_ext  = getFileExt($prdimg_R['name']);
				$prdimg_R_name = $prdcode."_R.".$prdimg_R_ext;

				copy($prdimg_R['tmp_name'],$imgpath."/".$prdimg_R_name);
				chmod($imgpath."/".$prdimg_R_name, 0606);

			}

		} else {
			$prdimg_R_name = $row->prdimg_R;
		}

		if(is_uploaded_file($_FILES['prdimg_L1']['tmp_name'])) {

			$prdimg_L1['size']     = $_FILES['prdimg_L1']['size'];
			$prdimg_L1['name']     = $_FILES['prdimg_L1']['name'];
			$prdimg_L1['tmp_name'] = $_FILES['prdimg_L1']['tmp_name'];

			if($prdimg_L1['size'] > 0) {

				ImageResize_Upload_check($prdimg_L1['name']);
//				$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $prdimg_L1['name']);
//				$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//				$prdimg_L1_ext = strtolower(substr($prdimg_L1['name'],$len));
				$prdimg_L1_ext = getFileExt($prdimg_L1['name']);
				
				$prdimg_L1_name = $prdcode."_L1.".$prdimg_L1_ext;

				copy($prdimg_L1['tmp_name'],$imgpath."/".$prdimg_L1_name);
				chmod($imgpath."/".$prdimg_L1_name, 0606);

			}

		} else {
			$prdimg_L1_name = $row->prdimg_L1;
		}

		if(is_uploaded_file($_FILES['prdimg_M1']['tmp_name'])) {

			$prdimg_M1['size']     = $_FILES['prdimg_M1']['size'];
			$prdimg_M1['name']     = $_FILES['prdimg_M1']['name'];
			$prdimg_M1['tmp_name'] = $_FILES['prdimg_M1']['tmp_name'];

			if($prdimg_M1['size'] > 0) {

				ImageResize_Upload_check($prdimg_M1['name']);
//				$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $prdimg_M1['name']);
//				$len = (strlen($ext) == 3) ? "-3" : "-4";
//				
//				$prdimg_M1_ext = strtolower(substr($prdimg_M1['name'],$len));
				
				$prdimg_M1_ext = getFileExt($prdimg_M1['name']);
				$prdimg_M1_name = $prdcode."_M1.".$prdimg_M1_ext;

				copy($prdimg_M1['tmp_name'],$imgpath."/".$prdimg_M1_name);
				chmod($imgpath."/".$prdimg_M1_name, 0606);

			}

		} else {
			$prdimg_M1_name = $row->prdimg_M1;
		}

		if(is_uploaded_file($_FILES['prdimg_S1']['tmp_name'])) {

			$prdimg_S1['size']     = $_FILES['prdimg_S1']['size'];
			$prdimg_S1['name']     = $_FILES['prdimg_S1']['name'];
			$prdimg_S1['tmp_name'] = $_FILES['prdimg_S1']['tmp_name'];

			if($prdimg_S1['size'] > 0) {

				ImageResize_Upload_check($prdimg_S1['name']);
//				$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $prdimg_S1['name']);
//				$len = (strlen($ext) == 3) ? "-3" : "-4";
//
//				$prdimg_S1_ext = strtolower(substr($prdimg_S1['name'],$len));
				$prdimg_S1_ext = getFileExt($prdimg_S1['name']);
				$prdimg_S1_name = $prdcode."_S1.".$prdimg_S1_ext;

				copy($prdimg_S1['tmp_name'],$imgpath."/".$prdimg_S1_name);
				chmod($imgpath."/".$prdimg_S1_name, 0606);

			}

		} else {
			$prdimg_S1_name = $row->prdimg_S1;
		}

	}


	for($ii = 2; $ii <= $prdimg_max; $ii++) {

		if($_FILES['realimg'.$ii]['size'] <= 0){

			if(!isset($_FILES['prdimg_L'.$ii]['tmp_name'])) $_FILES['prdimg_L'.$ii]['tmp_name'] = '';
			if(is_uploaded_file($_FILES['prdimg_L'.$ii]['tmp_name'])) {

				if($_FILES['prdimg_L'.$ii]['size'] > 0) {

					ImageResize_Upload_check($_FILES['prdimg_L'.$ii]['name']);
					//${'ext'.$ii} = preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['prdimg_L'.$ii]['name']);
					//${'len'.$ii} = (strlen(${'ext'.$ii}) == 3) ? "-3" : "-4";

					if(!empty($row->{'prdimg_L'.$ii})) @unlink($imgpath."/".$row->{'prdimg_L'.$ii});

					//${'prdimg_L'.$ii.'_ext'}  = strtolower(substr($_FILES['prdimg_L'.$ii]['name'],${'len'.$ii}));
					${'prdimg_L'.$ii.'_ext'}  = getFileExt($_FILES['prdimg_L'.$ii]['name']);

					${'prdimg_L'.$ii.'_name'} = $prdcode."_L".$ii.".".${'prdimg_L'.$ii.'_ext'};

					copy($_FILES['prdimg_L'.$ii]['tmp_name'],$imgpath."/".${'prdimg_L'.$ii.'_name'});
					chmod($imgpath."/".${'prdimg_L'.$ii.'_name'}, 0606);

				}

			} else {
				${'prdimg_L'.$ii.'_name'} = $row->{'prdimg_L'.$ii};
			}
			
			if(!isset($_FILES['prdimg_M'.$ii]['tmp_name'])) $_FILES['prdimg_M'.$ii]['tmp_name'] = '';
			if(is_uploaded_file($_FILES['prdimg_M'.$ii]['tmp_name'])) {

				if($_FILES['prdimg_M'.$ii]['size'] > 0) {

					ImageResize_Upload_check($_FILES['prdimg_M'.$ii]['name']);
					//${'ext'.$ii} = preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['prdimg_M'.$ii]['name']);
					//${'len'.$ii} = (strlen(${'ext'.$ii}) == 3) ? "-3" : "-4";

					if(!empty($row->{'prdimg_M'.$ii})) @unlink($imgpath."/".$row->{'prdimg_M'.$ii});

					//${'prdimg_M'.$ii.'_ext'} = strtolower(substr($_FILES['prdimg_M'.$ii]['name'],${'len'.$ii}));
					${'prdimg_M'.$ii.'_ext'} = getFileExt($_FILES['prdimg_M'.$ii]['name']);

					${'prdimg_M'.$ii.'_name'} = $prdcode."_M".$ii.".".${'prdimg_M'.$ii.'_ext'};
					copy($_FILES['prdimg_M'.$ii]['tmp_name'],$imgpath."/".${'prdimg_M'.$ii.'_name'});
					chmod($imgpath."/".${'prdimg_M'.$ii.'_name'}, 0606);

				}

			} else {
				${'prdimg_M'.$ii.'_name'} = $row->{'prdimg_M'.$ii};
			}
			
			if(!isset($_FILES['prdimg_S'.$ii]['tmp_name'])) $_FILES['prdimg_S'.$ii]['tmp_name'] = '';
			if(is_uploaded_file($_FILES['prdimg_S'.$ii]['tmp_name'])) {

				if($_FILES['prdimg_S'.$ii]['size'] > 0){

					ImageResize_Upload_check($_FILES['prdimg_S'.$ii]['name']);
					//${'ext'.$ii} = preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['prdimg_S'.$ii]['name']);
					//${'len'.$ii} = (strlen(${'ext'.$ii}) == 3) ? "-3" : "-4";

					if(!empty($row->{'prdimg_S'.$ii})) @unlink($imgpath."/".$row->{'prdimg_S'.$ii});

					//${'prdimg_S'.$ii.'_ext'} = strtolower(substr($_FILES['prdimg_S'.$ii]['name'],${'len'.$ii}));
					${'prdimg_S'.$ii.'_ext'} = getFileExt($_FILES['prdimg_S'.$ii]['name']);
					${'prdimg_S'.$ii.'_name'} = $prdcode."_S".$ii.".".${'prdimg_S'.$ii.'_ext'};
					copy($_FILES['prdimg_S'.$ii]['tmp_name'],$imgpath."/".${'prdimg_S'.$ii.'_name'});
					chmod($imgpath."/".${'prdimg_S'.$ii.'_name'}, 0606);

				}

			} else {
				${'prdimg_S'.$ii.'_name'} = $row->{'prdimg_S'.$ii};
			}

		}

	}

?>
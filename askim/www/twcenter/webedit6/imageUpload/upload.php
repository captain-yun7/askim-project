<?php
require_once("config.php");
//----------------------------------------------------------------------------
// 이미지 원본 이름
// $orig_name = $_FILES['file']['original_name'];

$temp_name = $_FILES['file']['tmp_name'];
$file_name = $_FILES['file']['name'];
$file_name_len = strrpos($file_name, ".");
$file_type = substr($file_name, $file_name_len + 1);
$found = false;

switch ($file_type) {
	case "jpg":
	case "jpeg":
	case "gif":
	case "png":
	case "webp":
		$found = true;
        break;
    default:
        $found = false;
}

if (!$found) {
    $rdata = ['result' => -1, 'message' => '파일 오류'];
} else {
    $save_file = SAVE_DIR . '/' . $file_name;

    move_uploaded_file($temp_name, $save_file);

    $image_size = getimagesize($save_file);
    $file_size = filesize($save_file);

    if (!$image_size || $file_size < 1) {
        unlink($save_file);
        $save_file = '';
        $file_name = '';
        $result_message = '전송 실패';
        $result = 0;
    } else {
		$result_message = '전송 완료';
		$result = 1;
	}

    $file_url = $file_size ? sprintf("%s/%s", SAVE_URL, $file_name) : '';

    $rdata = [
        'fileUrl'   => $file_url,
        'filePath'  => $save_file,
        'fileName'  => $file_name,
        'result'    => $result,
        'message'   => $result_message
    ];
}

header('Content-type: application/json');
echo json_encode($rdata);

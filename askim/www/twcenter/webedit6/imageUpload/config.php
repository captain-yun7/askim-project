<?php
include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

// ---------------------------------------------------------------------------

# 이미지가 저장될 디렉토리의 전체 경로를 설정합니다.
# 끝에 슬래쉬(/)는 붙이지 않습니다.
# 주의: 이 경로의 접근 권한은 쓰기, 읽기가 가능하도록 설정해 주십시오.

$create_date = date("ym");
$editor_url  = WAY_HOST."/".WIZHOME_DIR."/".WIZHOME_DATA_DIR."/webedit/".$create_date;
$editor_dir  = WIZHOME_PATH."/".WIZHOME_DATA_DIR."/webedit/".$create_date;

define("SAVE_DIR", $editor_dir);

# 위에서 설정한 'SAVE_DIR'의 URL을 설정합니다.
# 끝에 슬래쉬(/)는 붙이지 않습니다.

define("SAVE_URL", $editor_url);

if(!is_dir(SAVE_DIR)) mkdir(SAVE_DIR, 0707); chmod(SAVE_DIR, 0707);
// ---------------------------------------------------------------------------
?>

<?php
include "./common.php";

// 로그 파일 경로 확인
$log_path = LOG_PATH . date("Ymd") . "_test.log";

// 경로를 확인하기 위해 에러 로그로 출력
error_log("LOG_PATH 확인용 경로: " . $log_path);

// 로그 파일 열기 시도
$fp = @fopen($log_path, "a+");
if ($fp === false) {
    error_log("❌ fopen 실패 - 경로: " . $log_path);
    die("로그 파일 열기 실패: " . $log_path);
}

// 로그 쓰기 시도
fwrite($fp, "[" . date("Y-m-d H:i:s") . "] 테스트 로그 기록\n");
fclose($fp);

echo "✅ 로그 기록 성공: " . $log_path;

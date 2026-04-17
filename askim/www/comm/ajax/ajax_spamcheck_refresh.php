<?php
include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

	$tmp_str = substr(md5(rand()),0,12); // 임의의 md5 문자열을 생성

	list($usec, $sec) = explode(' ', microtime()); // 난수 발생기
	$seed =  (float)$sec + ((float)$usec * 100000);
	srand((int)$seed);
	$keylen = strlen($tmp_str);
	$div = (int)($keylen / 2);
	if(!isset($arr)) $arr = Array();
	while (count($arr) < 6)
	{
	    unset($arr);
	    for ($i=0; $i<$keylen; $i++)
	    {
	        $rnd = rand(1, $keylen);
	        $arr[$rnd] = $rnd;
	        if ($rnd > $div) break;
	    }
	}

	sort($arr);	// 배열에 저장된 숫자를 차례대로 정렬

	$norobot_key = "";
	$norobot_str = "";
	$m = 0;

	for ($i=0; $i<count($arr); $i++)
	{
	    for ($k=$m; $k<$arr[$i]-1; $k++)
	        $norobot_str .= $tmp_str[$k];
	    $norobot_str .= "<font size=3 color=#FF0000><b>{$tmp_str[$k]}</b></font>";
	    $norobot_key .= $tmp_str[$k];
	    $m = $k + 1;

	}

	if ($m < $keylen) {
	    for ($k=$m; $k<$keylen; $k++)
	        $norobot_str .= $tmp_str[$k];
	}

	echo $norobot_key;

?>
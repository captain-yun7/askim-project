<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class qfile {

	var $filePath;
	var $fileData;
	var $fileTemp;

	var $tmpPath;
	var $tmpPathLock;

	var $fpLock;
	var $fpTmp;


	function qfile() {
		$this->tmpPath = dirname(__FILE__) . "/../../data/tmp/chkQuota";
		$this->tmpPathLock = dirname(__FILE__) . "/../../data/tmp/chkQuotaLock";
	}


	function open($filepath) {

		if(!is_file($this->tmpPathLock)) die("파일작성중 오류가 발생했습니다. tmp작성에 실패했습니다");

		$this->filePath = $filepath;
		$this->fileData = '';
		$this->fileTemp = fopen($filepath, 'r');

		$this->fpLock = fopen($this->tmpPathLock,'w');

		if(!flock($this->fpLock, LOCK_EX))
		{
			return false;
		}

		$this->fpTmp = fopen($this->tmpPath, "w");


	}

	function read($type = null){
		if($type == 'get' || $type == 1){
			if(!$this->filePath || !$this->fileTemp) die("지정된 파일이 없습니다. 파일을 우선 지정해주세요");
			$gets = fgets($this->fileTemp, filesize($this->filePath));
			if($gets === false || feof($this->fileTemp)){
				fclose($this->fileTemp);
			}
			return $gets;
		}else{
			$fpOri = fopen($this->filePath, 'r');
			$read = fread($fpOri, filesize($this->filePath));
			fclose($fpOri);
			return $read;
		}
	}

	function write($string) {
		if($this->fpTmp==false) return false;
		if(fwrite($this->fpTmp,$string)===false) die("파일작성중 오류가 발생했습니다. 계정용량이나 파일권한을 확인해야합니다.");
		$this->fileData.=$string;
	}

	function close() {
		if($this->fpTmp==false) return false;
		fclose($this->fpTmp);
		$this->fpTmp = fopen($this->tmpPath, "w");
		fclose($this->fpTmp);

		$fpOri = fopen($this->filePath, "w");
		fwrite($fpOri,$this->fileData);
		fclose($fpOri);


		flock($this->fpLock, LOCK_UN);
		fclose($this->fpLock);

		$this->fpLock=false;
		$this->fpTmp=false;

	}
}

?>
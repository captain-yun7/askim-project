<?
Class DB {

	var $dbhost;
	var $dbuser;
	var $dbpass;
	var $dbname;
	var $dbconn;

	var $sql;
	var $res;

	var $ErrorCode;
	var $ErrorMsg;

	function DB($DBConf, $dbhost = "" , $dbuser = "" , $dbpass = "" , $dbname = "" )
	{

		if($dbhost) $this->dbhost = $dbhost;
		else        $this->dbhost = $DBConf['db_host'];
		if($dbname) $this->dbuser = $dbuser;
		else        $this->dbuser = $DBConf['db_user'];
		if($dbpass) $this->dbpass = $dbpass;
		else        $this->dbpass = $DBConf['db_pass'];
		if($dbname) $this->dbname = $dbname;
		else        $this->dbname = $DBConf['db_name'];

	}

	function connect()
	{

		if(is_resource($this->dbconn)) return true;

		if(function_exists('mysqli_connect') && extension_loaded('mysqli') && MYSQLI_USE) {
			$dbhost = explode(":", $this->dbhost);
			if (isset($dbhost[1])) $connect = mysqli_connect($dbhost[0], $this->dbuser, $this->dbpass, $this->dbname, $dbhost[1]);
			else				   $connect = mysqli_connect($dbhost[0], $this->dbuser, $this->dbpass, $this->dbname);

			if(!$connect) die("DBconn Error: ".mysqli_connect_error());
		} else {
			$connect = mysql_connect($dbhost, $dbuser, $dbpass);
			if(!$connect) die("DBconn Error: ".mysql_connect_error());
		}

		$this->dbconn = $connect;	//mysqli_connect($this->dbhost,$this->dbuser,$this->dbpass);

		if(!$this->dbconn)
		{
			$this->ERROR();
		}
	}       //End connect


	function ERROR()
	{
		if(function_exists('mysqli_connect') && extension_loaded('mysqli') && MYSQLI_USE) {
			$this->ErrorCode = mysqli_errno($this->dbconn);
			$this->ErrorMsg = mysqli_error($this->dbconn);
		} else {
			$this->ErrorCode = mysql_errno($this->dbconn);
			$this->ErrorMsg = mysql_error($this->dbconn);
		}
		echo("ErrorCode : $this->ErrorCode <br>ErrorMsg : $this->ErrorMsg");
		exit;
	}        //End ERROR


	function query($sql)
	{
		$this->connect();

		$this->sql = $sql;

		if(function_exists('mysqli_query') && extension_loaded('mysqli') && MYSQLI_USE) {
			$this->res = mysqli_query($this->dbconn,$this->sql);
		} else {
			$this->res = mysqli_query($this->sql, $this->dbconn);
		}
		if(!$this->res)       //잘못된 쿼리이면....
		{
			$this->ERROR();
		}
		return $this->res;
	}        //End query


	function insert_query($table,$fields,$values)   //insert 할 테이블명과 필드명(배열),값(배열)을 넘김
	{
		$this->connect();

		$fields_count = count($fields);
		$values_count = count($values);

		if($fields_count != $values_count) return false;   //필드갯수와 value값의 갯수가 맞지 않으면 false

		for( $i = 0 ; $i < $fields_count ; $i++ )
		{
			if(0 < $i){
				$fields_que .=",";
				$values_que .=",";
			}
			$fields_que .= $fields[$i];
			$values_que .="'".$values[$i]."'";
		}      //for End

		$sql = "indert into $table ($fields_que) values ($values_que)";      //쿼리생성
		$this->query($sql);
	}                 //End insert_query

	function update_query($table,$fields,$values,$where="") //update 할 테이블명과 필드명(배열),값(배열),조건절(where)을 넘김
	{

		$this->connect();

		if(is_array($fields) && is_array($values))    //fields 와 values가 배열이면
		{

			$fields_count = count($fields);
			$values_count = count($values);

			if($fields_count != $values_count) return false;  //필드갯수와 value값의 갯수가 맞지 않으면 false

			for($i = 0 ; $i < $fields_count ; $i++)
			{
				if(0 < $i) $sub_que .= ",";
				$sub_que .= $fields[$i] . "='" . $values[$i] . "'";
			}

		} elseif(!is_array($fields) && !is_array($values)) {   //fields 와 values가 배열이 아니면

			$sub_que = " " . $fields . "='" . $values . "' ";

		} else {                                              //fields 와 values 중 하나가 배열이고 하나가 배열이 아니면 false

			return false;

		}

		if($where) $sub_que .= " " . $where;               //조건절이 있을경우

		$sql = "update $table set $sub_que";
		$this->query($sql);

	}                   //End update_query

//-------insert_query() 와 update_query() 사용법----------------
//      $fields[] = "name";            $values[] = "이름값";
//      $fields[] = "email";           %values[] = "메일주소";
//      $DB->insery_query($table_name,$fields,$values);     <<-- update_query()도 같은 방법
//      $rows = $DB->affected_rows();      <<-- update,insert 실행후 반영된 레코드수 반환

	function select_query($table,$where,$field="*")   //select 시에 사용(사용안 하는게 좋음)
	{
		$sql = "select $field from $table $where";
		$this->query($sql);
	}

	function delete_query($table,$where)             //delete 시에 사용(사용안 하는게 좋음)
	{
		$sql = "delete from $table $where";
		$this->query($sql);
	}


	function affected_rows($res)   //insert,update,delete 쿼리실행후 적용된 rows 수를 반환
	{
		return mysqli_affected_rows($res);
	}

	function num_rows($res)        //결과로 부터 행 갯수반환
	{
		return mysqli_num_rows($res);
	}

	function fetch_array($res)     //결과셋에서 한행의 데이타를 필드이름 색인 또는 숫자색인으로 된 배열로 반환
	{                          // ex) while($row = $DB->fetch_array()){......$row[0],$row['name'].......}
		if(function_exists('mysqli_fetch_assoc') && extension_loaded('mysqli') && MYSQLI_USE) {
			return @mysqli_fetch_assoc($res);
		} else {
			return @mysql_fetch_assoc($res);
		}
	}

	function data_seek($i)     //내부적인 결과 포인트로 이동
	{
		return mysqli_data_seek($this->res,$i);
	}

	function num_fields()     //결과셋에서 필드수 반환
	{
		return mysqli_num_fields($this->res);
	}

	function field_name($i)   //결과셋에서 $i번째에 해당하는 필드명 반환
	{
		return mysqli_field_name($this->res,$i);
	}

	function delete()         //생성된 변수를 메모리에서 해제
	{
		unset($this->dbhost);
		unset($this->dbuser);
		unset($this->dbpass);
		unset($this->dbname);
		unset($this->dbconn);
		unset($this->sql);
		unset($this->res);
	}

	function destory()
	{
		if(is_resource($this->res)) mysqli_free_result($this->res);
	}

	function close()
	{
		if(is_resource($this->dbconn)) mysqli_close($this->dbconn);
	}

}           //End Class
?>
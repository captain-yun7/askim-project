<?php
class DB {
	private string $dbhost;
	private string $dbuser;
	private string $dbpass;
	private string $dbname;
	private ?mysqli $dbconn = null;

	private string $sql = '';
	private mysqli_result|bool|null $res = null;

	private int $ErrorCode = 0;
	private string $ErrorMsg = '';

	// ===== Getter 메서드 추가 (외부에서 읽기 전용 접근 허용) =====
	public function getDbHost(): string {
		return $this->dbhost;
	}

	public function getDbUser(): string {
		return $this->dbuser;
	}

	public function getDbName(): string {
		return $this->dbname;
	}

	public function getDbConn(): mysqli {
		return $this->dbconn;
	}

	public function getLastQuery(): string {
		return $this->sql;
	}

	public function getLastError(): array {
		return [
			'code' => $this->ErrorCode,
			'message' => $this->ErrorMsg,
		];
	}


	public function __construct(array $DBConf, string $dbhost = '', string $dbuser = '', string $dbpass = '', string $dbname = '') {
		$this->dbhost = $dbhost ?: $DBConf['db_host'];
		$this->dbuser = $dbuser ?: $DBConf['db_user'];
		$this->dbpass = $dbpass ?: $DBConf['db_pass'];
		$this->dbname = $dbname ?: $DBConf['db_name'];
	}

	public function connect(): void {
		if (isset($this->dbconn) && $this->dbconn instanceof mysqli) {
			return;
		}

		$hostParts = explode(':', $this->dbhost);
		$host = $hostParts[0];
		$port = $hostParts[1] ?? ini_get("mysqli.default_port");

		$this->dbconn = @new mysqli($host, $this->dbuser, $this->dbpass, $this->dbname, (int)$port);

		if ($this->dbconn->connect_error) {
			$this->ERROR();
		}
	}

	private function ERROR(): void {
		$this->ErrorCode = $this->dbconn->connect_errno ?: $this->dbconn->errno;
		$this->ErrorMsg = $this->dbconn->connect_error ?: $this->dbconn->error;
		die("ErrorCode: {$this->ErrorCode} <br>ErrorMsg: {$this->ErrorMsg}");
	}

	public function query(string $sql): mysqli_result|bool {
		$this->connect();
		$this->sql = $sql;
		$this->res = $this->dbconn->query($this->sql);

		if ($this->res === false) {
			$this->ERROR();
		}
		return $this->res;
	}

	public function insert_query(string $table, array $fields, array $values): bool {
		if (count($fields) !== count($values)) return false;

		$fieldList = implode(',', $fields);
		$valueList = implode(',', array_map(fn($v) => "'" . $this->dbconn->real_escape_string($v) . "'", $values));

		$sql = "INSERT INTO $table ($fieldList) VALUES ($valueList)";
		$this->query($sql);
		return true;
	}

	public function update_query(string $table, array|string $fields, array|string $values, string $where = ''): bool {
		$this->connect();

		if (is_array($fields) && is_array($values)) {
			if (count($fields) !== count($values)) return false;

			$set = [];
			for ($i = 0; $i < count($fields); $i++) {
				$f = $this->dbconn->real_escape_string($fields[$i]);
				$v = $this->dbconn->real_escape_string($values[$i]);
				$set[] = "$f='$v'";
			}
			$sub_que = implode(', ', $set);
		} elseif (is_string($fields) && is_string($values)) {
			$sub_que = "$fields='" . $this->dbconn->real_escape_string($values) . "'";
		} else {
			return false;
		}

		$sql = "UPDATE $table SET $sub_que";
		if ($where) $sql .= " $where";

		$this->query($sql);
		return true;
	}

	public function delete_query(string $table, string $where = ''): void {
		$sql = "DELETE FROM $table $where";
		$this->query($sql);
	}

	public function select_query(string $table, string $where = '', string $field = '*'): void {
		$sql = "SELECT $field FROM $table $where";
		$this->query($sql);
	}

	public function affected_rows(): int {
		return $this->dbconn->affected_rows;
	}

	public function num_rows(): int {
		if (!$this->res instanceof mysqli_result) return 0;
		return $this->res->num_rows;
	}

	public function fetch_array(): ?array {
		if (!$this->res instanceof mysqli_result) return null;
		return $this->res->fetch_assoc();
	}

	public function data_seek(int $i): bool {
		if (!$this->res instanceof mysqli_result) return false;
		return $this->res->data_seek($i);
	}

	public function num_fields(): int {
		if (!$this->res instanceof mysqli_result) return 0;
		return $this->res->field_count;
	}

	public function field_name(int $i): ?string {
		if (!$this->res instanceof mysqli_result) return null;
		$field = $this->res->fetch_field_direct($i);
		return $field?->name;
	}

	public function close(): void {
		if (isset($this->dbconn) && $this->dbconn instanceof mysqli) {
			if (@$this->dbconn->ping()) {
				$this->dbconn->close();
			}
			$this->dbconn = null;  // 연결 객체 초기화
		}
	}

	public function __destruct() {
		$this->close();
		if ($this->res instanceof mysqli_result) {
			$this->res->free();
		}
	}


}
?>

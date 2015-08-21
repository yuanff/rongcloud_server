<?php
require('./Helper.php');
class DataBase {
	private static $db = NULL;

	public static function getInstance($dsn, $user, $password){
		self::$db = new DataBase($dsn, $user, $password);
		return self::$db;
	}

	private $dbh;

	function __construct($dsn, $user, $password){
	        $serverName = env("MYSQL_PORT_3306_TCP_ADDR", "localhost");
           $databaseName = env("MYSQL_INSTANCE_NAME", "homestead");
           $username = env("MYSQL_USERNAME", "homestead");
           $password = env("MYSQL_PASSWORD", "secret");
           $this->dbh = new PDO("mysql:host=$serverName;dbname=$databaseName", $username, $password);

            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   $this->dbh->exec("SET NAMES utf8");
	}
	public function fetch($sql){
		$stmt = $this->prepare(func_get_args());
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetch();
	}

	public function fetchAll($sql){
		$stmt = $this->prepare(func_get_args());
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}

	public function fetchColumn($sql){
		$stmt = $this->prepare(func_get_args());
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function exec($sql){
		$stmt = $this->prepare(func_get_args());
		return $stmt->execute();
	}

	public function insert($sql){
		$stmt = $this->prepare(func_get_args());
		$stmt->execute();
		return $this->dbh->lastInsertId(); 
	}

	private function prepare(array $args){
		$stmt = $this->dbh->prepare($args[0]);
		for($i = 1; $i < count($args); $i++) {
			$arg = $args[$i];
			if ($arg instanceof BaseType) {
				$stmt->bindValue($i, $arg->val);
			} else if (is_array($arg)){

			} else {
				$stmt->bindValue($i, $arg);
			}
		}
		return $stmt;
	}
}

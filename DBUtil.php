<?php
require('./Helper.php');
class DataBase {
	private static $db = NULL;

	public static function getInstance($dsn, $user, $password){
		self::$db = new DataBase($dsn, $user, $password);
		return self::$db;
	}

	private $dbh;

	function __construct(){
	   $serverName = env("MYSQL_PORT_3306_TCP_ADDR", "localhost");
           $databaseName = env("MYSQL_INSTANCE_NAME", "homestead");
           $username = env("MYSQL_USERNAME", "homestead");
           $password = env("MYSQL_PASSWORD", "secret");
           $this->dbh = new PDO("mysql:host=$serverName;dbname=$databaseName", $username, $password);

            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   $this->dbh->exec("SET NAMES utf8");
	   $this->dbh->exec("DROP TABLE IF EXISTS `friend`;
CREATE TABLE `friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`user_id`,`friend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=366 DEFAULT CHARSET=utf8mb4;");
          $this->dbh->exec("DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `portrait` varchar(128) DEFAULT NULL,
  `introduce` varchar(256) DEFAULT NULL,
  `number` int(11) NOT NULL DEFAULT '1',
  `max_number` int(11) NOT NULL DEFAULT '500',
  `create_user_id` int(11) NOT NULL,
  `creat_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;");
	  $this->dbh->exec("DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `NewIndex1` (`group_id`),
  KEY `NewIndex2` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8mb4;");
	  $this->dbh->exec("DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `username` varchar(32) NOT NULL,
  `portrait` varchar(128) NOT NULL,
  `passwd` varchar(32) NOT NULL,
  `createdTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2543 DEFAULT CHARSET=utf8mb4;");
	}

	function __construct($dsn, $user, $password){
	        $this->dbh = new PDO($dsn, $user, $password);
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

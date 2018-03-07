<?php
class DB {
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_result,
			$_error = false,
			$_count = 0;

	private function __construct() {
		try{
			$this->_pdo = new PDO('mysql:host='. Config::getConfig("mysql/host") .
			 						';dbname=' . Config::getConfig("mysql/dbname"), 
												 Config::getConfig("mysql/username"),
												 Config::getConfig("mysql/password"));
			// $this->_pdo = new PDO('mysql:host=localhost;dbname=registerSys','root','');

		} catch(PDOException $e){
			die($e->getMessage());
		}
	}
	public static function getInstance(){ // why we did this function instead of making __contruct public? IDK
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	public function query($sql,$params = array()){
		$this->_error = false;

		if($this->_query = $this->_pdo->prepare($sql)){
			if(count($params)){
				$x = 1;
				foreach($params as $param){
					$this->_query->bindValue($x,$param);
					$x++;
				}
			}
		}

		if($this->_query->execute()) {
			$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count = $this->_query->rowCount();
		} else {
			$this->_error = true;
		}

		return $this;
	}
	public function select($field,$table,$where,$operator,$value){
		$sql = "SELECT $field FROM $table WHERE $where $operator ? ";
		if(!$this->query($sql,array($value))->error()){
			return $this;
		}
	}
	public function delete($table,$field,$operator,$value){
		$sql = "DELETE FROM $table WHERE $field $operator ?";
		if(!$this->query($sql,array($value))->error()){
			return $this;
		}
	}
	public function insert($table,$fields=array()){
		if(count($fields)){
			$keys = array_keys($fields);
			$value = NULL;
			$x= 1;
			foreach($fields as $field){
				$value .= "?";
				if($x < count($fields)){
					$value .= ",";
				}
				$x++;

			}
		}
		$sql = "INSERT INTO {$table}(`" . implode('`,`' , $keys) . "`) VALUES({$value})"; // what is this notaion ' ` ' ?
		if(!$this->query($sql,$fields)->error()){
			return $this;
			// INSERT INTO USERS('USERNAME','PASSWORD') VALUES({$value});
			return true;
		}
		return false;
	}
	public function update($table, $id, $fields = array()){
		if(count($fields)){
			$set = "";
			$x = 1;
			foreach($fields as $key=>$value){
				$set .= "{$key} = ?";
				if($x < count($fields)){
					$set .= ",";
				}
				$x++;
			}
			
			
		}
		$sql = "UPDATE $table SET {$set} WHERE ID = {$id}";
		if(!$this->query($sql,$fields)->error()){
			return true;
		}
		return false;
	}
	public function results(){
		return $this->_result;
	}
	public function first(){
		return $this->results()[0];
	}
	public function error(){
		return $this->_error;
	}
	public function count(){
		return $this->_count;
	}
}
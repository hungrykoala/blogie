<?php

	class MainProcess {
		private static $con=null;
		public static function con(){
			$hostname = "localhost";
			$dbname   = "blogie";
			$username = "root";
			$password = "";
			
			if( !self::$con){
				self::$con = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);   
				self::$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				self::$con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);           
			}
		   
			return self::$con;
		}
		
		public function select($field = null, $table = null, $where = null, $params = null){
			if(sizeof($field) > 1){
				$table_field = implode(",", $field);
			}else{
				$table_field = $field[0];
			}
			$return = $this->con()->prepare("SELECT {$table_field} FROM {$table} {$where}");
			$return->execute($params);
			return $return->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function insert($field = null, $table = null, $values = null, $params = null){
			if(sizeof($field) > 1){
				$table_field = implode(",", $field);
			}else{
				$table_field = $field[0];
			}
			$values = implode(",",$values);
			$return = $this->con()->prepare("INSERT INTO {$table} ({$table_field}) VALUES ({$values})");
			
			if($return->execute($params)){
				return true;
			}
		}
		
		public function delete_record($table = null, $where = null, $params = null){
			$return = $this->con()->prepare("DELETE FROM {$table} {$where}");
			if($return->execute($params)){
				return true;
			}
		}
		
		public function update_record($table = null, $where = null, $params = null){
			$return = $this->con()->prepare("UPDATE {$table} SET {$where}");
			if($return->execute($params)){
				return true;
			}
		}
		
		public function getName($user_id = null){
			$field[] = "name";
			$table = "user";
			$where = "WHERE id=:id";
			$params = array(':id'=>$user_id);
			$result = $this->select($field, $table, $where, $params);
			if(sizeof($result) > 0){
				$name   = $result[0]['name'];
				return $name;
			}
		}
	}

?>
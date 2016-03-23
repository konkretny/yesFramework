<?php
namespace Core\Classess;

class Db{
	
	public static function pdo_insert($query,$var = array()){
		global $PDO;
		global $database_type;
		try{
				$qr = $PDO->prepare($query);
				$i=1;
				foreach($var as $value){
					$qr->bindValue($i, $value);
					$i++;
				}
		
				$qr->execute();
				if($database_type == 'mysql:'){$last_id = $PDO->lastInsertId();}
				if($database_type == 'pgsql:'){$last_id = $qr->fetch(PDO::FETCH_ASSOC);}
				$qr->closeCursor();
			}
			catch(PDOException $e) {
				echo 'Connection error: ' . $e->getMessage();
		}
		return $last_id;
	}
	
	public static function pdo_update($query,$var = array()){
		global $PDO;
		global $database_type;
		try{
				$qr = $PDO->prepare($query);
				$i=1;
				foreach($var as $value){
					$qr->bindValue($i, $value);
					$i++;
				}
		
				$result = $qr->execute();
				$qr->closeCursor();
			}
			catch(PDOException $e) {
				echo 'Connection error: ' . $e->getMessage();
		}
		return $result;
	}
	
	public static function pdo_query($query){
		global $PDO;
		try{
				$qr = $PDO->prepare($query);
				$result = $qr->execute();
				$qr->closeCursor();
			}
			catch(PDOException $e) {
				echo 'Connection error: ' . $e->getMessage();
		}
		return $result;
	}
	
	public static function pdo_read($query,$var = array()){
		global $PDO;
		global $database_type;

		try{
				$qr = $PDO->prepare($query);
				$i=1;
				foreach($var as $value){
					$qr->bindValue($i, $value);
					$i++;
				}
		
				$qr->execute();
				$result = $qr->fetchAll();
				$qr->closeCursor();
			}
			catch(PDOException $e) {
				echo 'Connection error: ' . $e->getMessage();
		}
		return $result;
	}
	
	public static function pdo_delete($query,$var = array()){
		global $PDO;
		global $database_type;
		try{
				$qr = $PDO->prepare($query);
				$i=1;
				foreach($var as $value){
					$qr->bindValue($i, $value);
					$i++;
				}
		
				$result = $qr->execute();
				$qr->closeCursor();
			}
			catch(PDOException $e) {
				echo 'Connection error: ' . $e->getMessage();
		}
		return $result;
	}
	
	public static function pdo_transaction($query = array()){
		global $PDO;
		global $database_type;
		try{
			$e=1;
			$PDO->beginTransaction(); 
				foreach($query as $value){
						$qr = $PDO->prepare($value[0]);
						$i=1;
						foreach($value[1] as $bindvalue){
							$qr->bindValue($i, $bindvalue);
							$i++;
						}
					$qr->execute();
				}
			  
			
			}
			catch(PDOException $e) {
				$PDO->rollBack();
				echo 'Connection error: ' . $e->getMessage();
			}
			if($qr->errorInfo()[0]==0){
				$PDO->commit();
			}else{
				$PDO->rollBack();
				$e=errorInfo();
			}
		
		return $e;
	}
	
}
?>
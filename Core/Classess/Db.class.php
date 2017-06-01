<?php
namespace Core\Classess;

/**
 * Class database support
 */
class Db{
	
        /**
         * Executes the query INSERT
         * @global object $PDO
         * @global int $database_type
         * @param string $query
         * @param mixed[] $var
         * @return int
         */
	public static function pdo_insert($query,$var = array()){
		global $PDO;
		global $database_type;
                $last_id=0;
		try{
				$qr = $PDO->prepare($query);
				$i=1;
				foreach($var as $value){
                                    if(true_empty($value)){$value='';}
					$qr->bindValue($i, $value);
					$i++;
				}
		
				$qr->execute();
				if($database_type == 'mysql:' || $database_type == 'sqlite:'){$last_id = $PDO->lastInsertId();}
				if($database_type == 'pgsql:'){$last_id = $qr->fetch(PDO::FETCH_ASSOC);}
				$qr->closeCursor();
			}
			catch(PDOException $e) {
				echo 'Connection error: ' . $e->getMessage();
		}
		return $last_id;
	}
	
        /**
         * Executes the query UPDATE
         * @global object $PDO
         * @global int $database_type
         * @param string $query
         * @param mixed[] $var
         * @return int
         */
	public static function pdo_update($query,$var = array()){
		global $PDO;
		global $database_type;
                $result=0;
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
	
        /**
         * Executes the query
         * @global object $PDO
         * @param string $query
         * @return int
         */
	public static function pdo_query($query){
		global $PDO;
                $result=0;
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
	
        /**
         * Executes the query SELECT
         * @global object $PDO
         * @global int $database_type
         * @param string $query
         * @param mixed[] $var
         * @return string[]
         */
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
	
        /**
         * Executes the query DELETE
         * @global object $PDO
         * @global int $database_type
         * @param string $query
         * @param mixed[] $var
         * @return int
         */
	public static function pdo_delete($query,$var = array()){
		global $PDO;
		global $database_type;
                $result=0;
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
	
        /**
         * Executes transactions
         * @global object $PDO
         * @global int $database_type
         * @param string[] $query
         * @return int
         */
	public static function pdo_transaction($query = array()){
		global $PDO;
		global $database_type;
                $commit = true;
                $last_id=NULL;
		try{
			$e=1;
			$PDO->beginTransaction(); 
				foreach($query as $value){
						$qr = $PDO->prepare($value[0]);
						$i=1;
						foreach($value[1] as $bindvalue){
                                                        if($bindvalue=='last_id'){
                                                            $qr->bindValue($i, $last_id);   
                                                        }
                                                        else{
                                                            $qr->bindValue($i, $bindvalue);    
                                                        }
							$i++;
						}
					$result = $qr->execute();
                                            if($database_type == 'mysql:' || $database_type == 'sqlite:'){$last_id = $PDO->lastInsertId();}
                                            if($database_type == 'pgsql:'){$last_id = $qr->fetch(PDO::FETCH_ASSOC);}
                                        if(!$result){
                                            $commit=false;
                                            $e=0;
                                        }
				}
			  
			
			}
			catch(PDOException $e) {
				$PDO->rollBack();
				echo 'Connection error trans';
                                $commit = false;
                                $e=0;
			}
			if(!$commit){
                                $PDO->rollBack();
			}else{
				$PDO->commit();
				$e=$PDO->errorInfo();
			}
		
		return $commit;
	}
	
}
?>
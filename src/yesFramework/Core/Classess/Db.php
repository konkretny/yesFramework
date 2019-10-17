<?php

namespace yesFramework\Core\Classess;

interface DbInterface
{
	public static function pdo_insert(string $query, array $var = [], bool $secure_input = true, bool $key = false): int;
	public static function pdo_update(string $query, array $var = [], bool $execute_result = false, bool $secure_input = true, bool $key = false);
	public static function pdo_query(string $query): bool;
	public static function pdo_read(string $query, array $var = [], bool $key = false, bool $no_read_numbers): array;
	public static function pdo_delete(string $query, array $var = [], bool $key = false): int;
	public static function pdo_transaction(array $query = [], bool $key = false): bool;
}

/**
 * Class database support
 */
class Db implements DbInterface
{

	/**
	 * Executes the query INSERT
	 * @global object $PDO
	 * @global int $database_type
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $secure_input
	 * @return int
	 */
	public static function pdo_insert(string $query, array $var = [], bool $secure_input = true, bool $key = false): int
	{
		global $PDO;
		global $database_type;
		$last_id = 0;
		try {
			$qr = $PDO->prepare($query);
			if ($key === true) {
				foreach ($var as $key => $value) {
					if (true_empty($value)) {
						$value = '';
					}
					if ($secure_input === true) {
						$qr->bindValue($key, secure_input($value));
					} else {
						$qr->bindValue($key, $value);
					}
				}
			} else {
				$i = 1;
				foreach ($var as $value) {
					if (true_empty($value)) {
						$value = '';
					}
					if ($secure_input === true) {
						$qr->bindValue($i, secure_input($value));
					} else {
						$qr->bindValue($i, $value);
					}

					$i++;
				}
			}

			$qr->execute();
			if ($qr->rowCount() > 0 && $database_type == 'mysql:' || $database_type == 'sqlite:') {
				$last_id = $PDO->lastInsertId();
			}
			if ($qr->rowCount() > 0 && $database_type == 'pgsql:') {
				$last_id = $qr->fetch(\PDO::FETCH_ASSOC);
			}
			$qr->closeCursor();
		} catch (PDOException $e) {
			echo 'Database connection error.';
		}
		return $last_id;
	}

	/**
	 * Executes the query UPDATE
	 * @global object $PDO
	 * @global int $database_type
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $execute_result
	 * @param bool $secure_input
	 * @return int
	 */
	public static function pdo_update(string $query, array $var = [], bool $execute_result = false, bool $secure_input = true, bool $key = false)
	{
		global $PDO;
		$result = false;
		try {
			$qr = $PDO->prepare($query);
			if ($key === true) {
				foreach ($var as $key => $value) {
					if ($secure_input === true) {
						$qr->bindValue($key, secure_input($value));
					} else {
						$qr->bindValue($key, $value);
					}
				}
			} else {
				$i = 1;
				foreach ($var as $value) {
					$qr->bindValue($i, $value);
					if ($secure_input === true) {
						$qr->bindValue($i, secure_input($value));
					} else {
						$qr->bindValue($i, $value);
					}
					$i++;
				}
			}

			$result_execute = $qr->execute();
			$result = $qr->rowCount();
			$qr->closeCursor();
		} catch (PDOException $e) {
			echo 'Database connection error.';
		}
		if ($execute_result === true) {
			return $result_execute;
		} else {
			return $result;
		}
	}

	/**
	 * Executes the query
	 * @global object $PDO
	 * @param string $query
	 * @return int
	 */
	public static function pdo_query(string $query): bool
	{
		global $PDO;
		$result = 0;
		try {
			$qr = $PDO->prepare($query);
			$result = $qr->execute();
			$qr->closeCursor();
		} catch (PDOException $e) {
			echo 'Database connection error.';
		}
		return $result;
	}

	/**
	 * Executes the query SELECT
	 * @global object $PDO
	 * @global int $database_type
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $key
	 * @param bool $no_read_numbers
	 * @return string[]
	 */
	public static function pdo_read(string $query, array $var = [], bool $key = false, bool $no_read_numbers = false): array
	{
		global $PDO;
		try {
			$qr = $PDO->prepare($query);
			if ($key === true) {
				foreach ($var as $key => $value) {
					$qr->bindValue($key, $value);
				}
			} else {
				$i = 1;
				foreach ($var as $value) {
					$qr->bindValue($i, $value);
					$i++;
				}
			}

			$qr->execute();
			if($no_read_numbers===true){
				$result = $qr->fetchAll(\PDO::FETCH_ASSOC);
			}else{
				$result = $qr->fetchAll();
			}
			
			$qr->closeCursor();
		} catch (PDOException $e) {
			echo 'Database connection error.';
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
	public static function pdo_delete(string $query, array $var = [], bool $key = false): int
	{
		global $PDO;
		$result = false;
		try {
			$qr = $PDO->prepare($query);
			if ($key === true) {
				foreach ($var as $key => $value) {
					$qr->bindValue($key, $value);
				}
			} else {
				$i = 1;
				foreach ($var as $value) {
					$qr->bindValue($i, $value);
					$i++;
				}
			}

			$qr->execute();
			$result = $qr->rowCount();
			$qr->closeCursor();
		} catch (PDOException $e) {
			echo 'Database connection error.';
		}
		return $result;
	}

	/**
	 * Executes transactions
	 * @global object $PDO
	 * @global int $database_type
	 * @param string[] $query
	 * @return bool
	 */
	public static function pdo_transaction(array $query = [], bool $key = false): bool
	{
		global $PDO;
		global $database_type;
		$commit = true;
		$last_id = NULL;
		try {
			$e = 1;
			$PDO->beginTransaction();
			foreach ($query as $value) {
				$qr = $PDO->prepare($value[0]);
				if (key === true) {
					foreach ($value[1] as $key => $bindvalue) {
						if ($bindvalue == 'last_id') {
							$qr->bindValue($key, $last_id);
						} else {
							$qr->bindValue($key, $bindvalue);
						}
					}
				} else {
					$i = 1;
					foreach ($value[1] as $bindvalue) {
						if ($bindvalue == 'last_id') {
							$qr->bindValue($i, $last_id);
						} else {
							$qr->bindValue($i, $bindvalue);
						}
						$i++;
					}
				}
				$result = $qr->execute();
				if ($result === true && $database_type == 'mysql:' || $database_type == 'sqlite:') {
					$last_id = $PDO->lastInsertId();
				}
				if ($result === true && $database_type == 'pgsql:') {
					$last_id = $qr->fetch(\PDO::FETCH_ASSOC);
				}
				if (!$result) {
					$commit = false;
					$e = 0;
				}
			}
		} catch (PDOException $e) {
			$PDO->rollBack();
			echo 'Connection error trans';
			$commit = false;
			$e = 0;
		}
		if (!$commit) {
			$PDO->rollBack();
		} else {
			$PDO->commit();
			$e = $PDO->errorInfo();
		}

		return $commit;
	}
}

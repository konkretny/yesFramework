<?php

namespace yesFramework\Core\Classess;

interface DbInterface
{
	public function insert(string $query, array $var = [], bool $secure_input = true, bool $key = false): int|array;
	public function update(string $query, array $var = [], bool $execute_result = false, bool $secure_input = true, bool $key = false): int|bool;
	public function query(string $query): bool;
	public function read(string $query, array $var = [], bool $key = false): array;
	public function readNoNumbers(string $query, array $var = [], bool $key = false): array;
	public function delete(string $query, array $var = [], bool $key = false): int;
	public function transaction(array $query = [], bool $key = false): bool;
}

/**
 * Class database support
 */
class Db implements DbInterface
{
	private \PDO $pdo;
	private string $databaseType;

	public function __construct(\PDO $pdo, string $databaseType)
	{
		$this->pdo = $pdo;
		$this->databaseType = $databaseType;
	}

	/**
	 * Executes the query INSERT
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $secure_input
	 * @param bool $key
	 * @return int|array
	 */
	public function insert(string $query, array $var = [], bool $secure_input = true, bool $key = false): int|array
	{
		$last_id = 0;
		try {
			$qr = $this->pdo->prepare($query);
			if ($key === true) {
				foreach ($var as $key => $value) {
					if (Validator::isTrueEmpty($value)) {
						$value = '';
					}
					if ($secure_input === true) {
						$qr->bindValue($key, Str::secureInput((string)$value));
					} else {
						$qr->bindValue($key, $value);
					}
				}
			} else {
				$i = 1;
				foreach ($var as $value) {
					if (Validator::isTrueEmpty($value)) {
						$value = '';
					}
					if ($secure_input === true) {
						$qr->bindValue($i, Str::secureInput((string)$value));
					} else {
						$qr->bindValue($i, $value);
					}

					$i++;
				}
			}

			$qr->execute();
			if ($qr->rowCount() > 0 && ($this->databaseType == 'mysql:' || $this->databaseType == 'sqlite:')) {
				$last_id = (int)$this->pdo->lastInsertId();
			}
			if ($qr->rowCount() > 0 && $this->databaseType == 'pgsql:') {
				$last_id = $qr->fetch(\PDO::FETCH_ASSOC);
			}
			$qr->closeCursor();
		} catch (\PDOException $e) {
			echo 'Database connection error: ' . $e->getMessage();
		}
		return $last_id;
	}

	/**
	 * Executes the query UPDATE
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $execute_result
	 * @param bool $secure_input
	 * @param bool $key
	 * @return int|bool
	 */
	public function update(string $query, array $var = [], bool $execute_result = false, bool $secure_input = true, bool $key = false): int|bool
	{
		$result = false;
		$result_execute = false;
		try {
			$qr = $this->pdo->prepare($query);
			if ($key === true) {
				foreach ($var as $key => $value) {
					if ($secure_input === true) {
						$qr->bindValue($key, Str::secureInput((string)$value));
					} else {
						$qr->bindValue($key, $value);
					}
				}
			} else {
				$i = 1;
				foreach ($var as $value) {
					$qr->bindValue($i, $value);
					if ($secure_input === true) {
						$qr->bindValue($i, Str::secureInput((string)$value));
					} else {
						$qr->bindValue($i, $value);
					}
					$i++;
				}
			}

			$result_execute = $qr->execute();
			$result = $qr->rowCount();
			$qr->closeCursor();
		} catch (\PDOException $e) {
			echo 'Database connection error: ' . $e->getMessage();
		}
		if ($execute_result === true) {
			return $result_execute;
		} else {
			return $result;
		}
	}

	/**
	 * Executes the query
	 * @param string $query
	 * @return bool
	 */
	public function query(string $query): bool
	{
		$result = false;
		try {
			$qr = $this->pdo->prepare($query);
			$result = $qr->execute();
			$qr->closeCursor();
		} catch (\PDOException $e) {
			echo 'Database connection error: ' . $e->getMessage();
		}
		return $result;
	}

	/**
	 * Executes the query SELECT
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $key
	 * @return array
	 */
	public function read(string $query, array $var = [], bool $key = false): array
	{
		$result = [];
		try {
			$qr = $this->pdo->prepare($query);
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
			$result = $qr->fetchAll();
			$qr->closeCursor();
		} catch (\PDOException $e) {
			echo 'Database connection error: ' . $e->getMessage();
		}
		return $result;
	}

	/**
	 * Read witouch numbers
	 * @param string $query
	 * @param array $var
	 * @param bool $key
	 * @return array
	 */
	public function readNoNumbers(string $query, array $var = [], bool $key = false): array
	{
		$result = [];
		try {
			$qr = $this->pdo->prepare($query);
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
			$result = $qr->fetchAll(\PDO::FETCH_ASSOC);
			$qr->closeCursor();
		} catch (\PDOException $e) {
			echo 'Database connection error: ' . $e->getMessage();
		}
		return $result;
	}

	/**
	 * Executes the query DELETE
	 * @param string $query
	 * @param mixed[] $var
	 * @param bool $key
	 * @return int
	 */
	public function delete(string $query, array $var = [], bool $key = false): int
	{
		$result = 0;
		try {
			$qr = $this->pdo->prepare($query);
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
		} catch (\PDOException $e) {
			echo 'Database connection error: ' . $e->getMessage();
		}
		return $result;
	}

	/**
	 * Executes transactions
	 * @param array $query
	 * @param bool $key
	 * @return bool
	 */
	public function transaction(array $query = [], bool $key = false): bool
	{
		$commit = true;
		$last_id = NULL;
		try {
			$e = 1;
			$this->pdo->beginTransaction();
			foreach ($query as $value) {
				$qr = $this->pdo->prepare($value[0]);
				if ($key === true) {
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
				if ($result === true && ($this->databaseType == 'mysql:' || $this->databaseType == 'sqlite:')) {
					$last_id = $this->pdo->lastInsertId();
				}
				if ($result === true && $this->databaseType == 'pgsql:') {
					$last_id = $qr->fetch(\PDO::FETCH_ASSOC);
				}
				if (!$result) {
					$commit = false;
					$e = 0;
				}
			}
		} catch (\PDOException $ex) {
			$this->pdo->rollBack();
			echo 'Connection error trans: ' . $ex->getMessage();
			$commit = false;
			$e = 0;
		}
		if (!$commit) {
			$this->pdo->rollBack();
		} else {
			$this->pdo->commit();
		}

		return $commit;
	}
}

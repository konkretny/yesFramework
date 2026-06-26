<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

use yesFramework\Core\Exceptions\DatabaseException;

interface DbInterface
{
	public function insert(string $query, array $var = [], bool $key = false): int|array;
	public function update(string $query, array $var = [], bool $key = false): int;
	public function unsafeRawQuery(string $query): bool;
	public function read(string $query, array $var = [], bool $key = false): array;
	public function readNoNumbers(string $query, array $var = [], bool $key = false): array;
	public function delete(string $query, array $var = [], bool $key = false): int;
	public function transaction(array $query = [], bool $key = false): bool;
}

/**
 * Database support class with PDO
 *
 * Security notes:
 * - All queries use prepared statements with parameter binding (SQL injection safe)
 * - XSS protection should be applied at the OUTPUT layer (views), not here
 * - Use read()/insert()/update()/delete() with bound parameters for user input
 * - The unsafeRawQuery() method executes raw SQL — never pass user input to it
 */
class Db implements DbInterface
{
	private \PDO $pdo;
	private readonly DatabaseType $databaseType;

	public function __construct(\PDO $pdo, DatabaseType $databaseType)
	{
		$this->pdo = $pdo;
		$this->databaseType = $databaseType;
	}

	/**
	 * Bind parameters to a prepared statement
	 *
	 * Automatically detects the correct PDO type for each value:
	 * - int → PDO::PARAM_INT
	 * - bool → PDO::PARAM_BOOL
	 * - null → PDO::PARAM_NULL
	 * - everything else → PDO::PARAM_STR
	 *
	 * @param \PDOStatement $stmt
	 * @param array $var Parameters to bind
	 * @param bool $key If true, bind by named keys (:name). If false, bind by position (1, 2, 3...)
	 */
	private function bindParams(\PDOStatement $stmt, array $var, bool $key): void
	{
		if ($key) {
			foreach ($var as $name => $value) {
				$stmt->bindValue($name, $value, $this->detectParamType($value));
			}
		} else {
			$i = 1;
			foreach ($var as $value) {
				$stmt->bindValue($i, $value, $this->detectParamType($value));
				$i++;
			}
		}
	}

	/**
	 * Detect the appropriate PDO parameter type for a value
	 */
	private function detectParamType(mixed $value): int
	{
		return match (true) {
			is_int($value) => \PDO::PARAM_INT,
			is_bool($value) => \PDO::PARAM_BOOL,
			is_null($value) => \PDO::PARAM_NULL,
			default => \PDO::PARAM_STR,
		};
	}

	/**
	 * Execute an INSERT query
	 *
	 * @param string $query SQL query with placeholders
	 * @param mixed[] $var Parameters to bind
	 * @param bool $key Use named parameters (:name) instead of positional (?)
	 * @return int|array Last insert ID (int for MySQL/SQLite, array for PostgreSQL RETURNING)
	 *
	 * @throws DatabaseException
	 */
	public function insert(string $query, array $var = [], bool $key = false): int|array
	{
		$last_id = 0;
		try {
			$stmt = $this->pdo->prepare($query);
			$this->bindParams($stmt, $var, $key);
			$stmt->execute();

			if ($stmt->rowCount() > 0) {
				$last_id = match ($this->databaseType) {
					DatabaseType::MySQL, DatabaseType::SQLite => (int)$this->pdo->lastInsertId(),
					DatabaseType::PostgreSQL => $stmt->fetch(\PDO::FETCH_ASSOC),
				};
			}
			$stmt->closeCursor();
		} catch (\PDOException $e) {
			throw new DatabaseException('Insert query failed: ' . $e->getMessage(), 500, $e);
		}
		return $last_id;
	}

	/**
	 * Execute an UPDATE query
	 *
	 * @param string $query SQL query with placeholders
	 * @param mixed[] $var Parameters to bind
	 * @param bool $key Use named parameters (:name) instead of positional (?)
	 * @return int Number of affected rows
	 *
	 * @throws DatabaseException
	 */
	public function update(string $query, array $var = [], bool $key = false): int
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$this->bindParams($stmt, $var, $key);
			$stmt->execute();
			$result = $stmt->rowCount();
			$stmt->closeCursor();
		} catch (\PDOException $e) {
			throw new DatabaseException('Update query failed: ' . $e->getMessage(), 500, $e);
		}
		return $result;
	}

	/**
	 * Execute a raw SQL query (no parameter binding)
	 *
	 * WARNING: Never pass user input directly to this method.
	 * Use insert(), update(), read(), or delete() with bound parameters instead.
	 *
	 * @param string $query SQL query
	 * @return bool True on success
	 *
	 * @throws DatabaseException
	 */
	public function unsafeRawQuery(string $query): bool
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$result = $stmt->execute();
			$stmt->closeCursor();
		} catch (\PDOException $e) {
			throw new DatabaseException('Query failed: ' . $e->getMessage(), 500, $e);
		}
		return $result;
	}

	/**
	 * Execute a SELECT query (returns both numeric and associative indices)
	 *
	 * @param string $query SQL query with placeholders
	 * @param mixed[] $var Parameters to bind
	 * @param bool $key Use named parameters (:name) instead of positional (?)
	 * @return array Result rows
	 *
	 * @throws DatabaseException
	 */
	public function read(string $query, array $var = [], bool $key = false): array
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$this->bindParams($stmt, $var, $key);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$stmt->closeCursor();
		} catch (\PDOException $e) {
			throw new DatabaseException('Read query failed: ' . $e->getMessage(), 500, $e);
		}
		return $result;
	}

	/**
	 * Execute a SELECT query (returns associative array only, no numeric indices)
	 *
	 * @param string $query SQL query with placeholders
	 * @param mixed[] $var Parameters to bind
	 * @param bool $key Use named parameters (:name) instead of positional (?)
	 * @return array Result rows as associative arrays
	 *
	 * @throws DatabaseException
	 */
	public function readNoNumbers(string $query, array $var = [], bool $key = false): array
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$this->bindParams($stmt, $var, $key);
			$stmt->execute();
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$stmt->closeCursor();
		} catch (\PDOException $e) {
			throw new DatabaseException('Read query failed: ' . $e->getMessage(), 500, $e);
		}
		return $result;
	}

	/**
	 * Execute a DELETE query
	 *
	 * @param string $query SQL query with placeholders
	 * @param mixed[] $var Parameters to bind
	 * @param bool $key Use named parameters (:name) instead of positional (?)
	 * @return int Number of deleted rows
	 *
	 * @throws DatabaseException
	 */
	public function delete(string $query, array $var = [], bool $key = false): int
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$this->bindParams($stmt, $var, $key);
			$stmt->execute();
			$result = $stmt->rowCount();
			$stmt->closeCursor();
		} catch (\PDOException $e) {
			throw new DatabaseException('Delete query failed: ' . $e->getMessage(), 500, $e);
		}
		return $result;
	}

	/**
	 * Execute multiple queries in a database transaction
	 *
	 * All queries succeed together or are rolled back together.
	 * Use 'last_id' as a parameter value to reference the last inserted ID.
	 *
	 * @param array $query Array of [sql, params] pairs
	 * @param bool $key Use named parameters (:name) instead of positional (?)
	 * @return bool True if all queries committed successfully
	 *
	 * @throws DatabaseException
	 */
	public function transaction(array $query = [], bool $key = false): bool
	{
		$last_id = null;

		try {
			$this->pdo->beginTransaction();

			foreach ($query as $value) {
				$stmt = $this->pdo->prepare($value[0]);

				// Replace 'last_id' placeholder with the actual last inserted ID
				$params = [];
				foreach ($value[1] as $paramKey => $bindvalue) {
					$params[$paramKey] = ($bindvalue === 'last_id') ? $last_id : $bindvalue;
				}

				$this->bindParams($stmt, $params, $key);
				$result = $stmt->execute();

				if ($result) {
					$last_id = match ($this->databaseType) {
						DatabaseType::MySQL, DatabaseType::SQLite => $this->pdo->lastInsertId(),
						DatabaseType::PostgreSQL => $stmt->fetch(\PDO::FETCH_ASSOC),
					};
				} else {
					$this->pdo->rollBack();
					return false;
				}
			}

			$this->pdo->commit();
			return true;

		} catch (\PDOException $e) {
			if ($this->pdo->inTransaction()) {
				$this->pdo->rollBack();
			}
			throw new DatabaseException('Transaction failed: ' . $e->getMessage(), 500, $e);
		}
	}
}

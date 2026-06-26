<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

/**
 * Database type enumeration
 * Replaces magic numbers (0, 1, 2) with a type-safe enum
 */
enum DatabaseType: int
{
    case MySQL = 0;
    case PostgreSQL = 1;
    case SQLite = 2;

    /**
     * Returns the PDO DSN prefix for this database type
     */
    public function dsnPrefix(): string
    {
        return match ($this) {
            self::MySQL => 'mysql:',
            self::PostgreSQL => 'pgsql:',
            self::SQLite => 'sqlite:',
        };
    }
}

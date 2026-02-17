<?php

namespace Riddle\TgBotBase\Db\Seed;

use Riddle\TgBotBase\Db\MigrationDto;

class SeedMigration extends MigrationDto
{
    public const string TABLE_NAME = 'seeds';

    public function __construct()
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            file VARCHAR(255) UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        parent::__construct($createTableSql, []);
    }
}

<?php

namespace Riddle\TgBotBase\Log\Db;

use Riddle\TgBotBase\Db\MigrationDto;

class LogMigration extends MigrationDto
{
    public const string TABLE_NAME = 'logs';

    public function __construct()
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type VARCHAR(20),
            user_id INTEGER,
            text TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $indexSql = [
            "CREATE INDEX IF NOT EXISTS idx_type ON " . self::TABLE_NAME . " (type)",
            "CREATE INDEX IF NOT EXISTS idx_user_id ON " . self::TABLE_NAME . " (user_id)",
        ];

        parent::__construct($createTableSql, $indexSql);
    }
}

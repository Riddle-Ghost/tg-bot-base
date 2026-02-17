<?php

namespace Riddle\TgBotBase\User\Db;

use Riddle\TgBotBase\Db\MigrationDto;

class UserMigration extends MigrationDto
{
    public const string TABLE_NAME = 'users';

    public function __construct()
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tg_id BIGINT UNIQUE,
            username VARCHAR(255),
            is_premium BOOLEAN DEFAULT 0,
            is_blocked BOOLEAN DEFAULT 0,
            settings JSON DEFAULT '{}',
            created_at123 DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $indexSql = [
            "CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON " . self::TABLE_NAME . " (tg_id)",
        ];

        parent::__construct($createTableSql, $indexSql);
    }
}

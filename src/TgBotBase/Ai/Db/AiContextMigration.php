<?php

namespace Riddle\TgBotBase\Ai\Db;

use Riddle\TgBotBase\Db\MigrationDto;

class AiContextMigration extends MigrationDto
{
    public const string TABLE_NAME = 'aicontexts';

    public function __construct()
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tg_id BIGINT UNIQUE,
            context TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $indexSql = [
            "CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON " . self::TABLE_NAME . " (tg_id)",
        ];

        parent::__construct($createTableSql, $indexSql);
    }
}

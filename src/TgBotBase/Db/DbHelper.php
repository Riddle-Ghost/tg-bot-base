<?php

namespace Riddle\TgBotBase\Db;

require_once __DIR__ . '/rb-sqlite.php';

class DbHelper
{
    public static function init(DbConfig $config): void
    {
        \R::setup('sqlite:' . $config->dbDir . '/default.sqlite');
        \R::addDatabase('logs', 'sqlite:' . $config->dbDir . '/db_logs.sqlite');

        foreach ($config->sqlExecutions as $sql) {
            \R::exec($sql);
        }
        self::initTables();
        \R::freeze(true); // RedBean не будет пытаться менять структуру БД

        foreach ($config->sqlExecutionFiles as $file) {
            self::executeSql($file);
        }
    }

    private static function initTables(): void
    {
        self::initDefaultTables();
        self::initLogTables();
    }

    private static function initDefaultTables(): void
    {
        \R::exec("CREATE TABLE IF NOT EXISTS user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tg_id BIGINT UNIQUE,
            username VARCHAR(255),
            is_premium BOOLEAN DEFAULT 0,
            is_blocked BOOLEAN DEFAULT 0,
            settings JSON DEFAULT '{}',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        \R::exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON user (tg_id)");

        \R::exec("CREATE TABLE IF NOT EXISTS ai_context (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tg_id BIGINT UNIQUE,
            context TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        \R::exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON ai_context (tg_id)");
    }

    private static function initLogTables(): void
    {
        \R::selectDatabase('logs');

        \R::exec("CREATE TABLE IF NOT EXISTS log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type VARCHAR(20) UNIQUE,
            user_id INTEGER,
            text TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        \R::exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_type ON log (type)");
        \R::exec("CREATE  INDEX IF NOT EXISTS idx_user_id ON log (user_id)");

        \R::selectDatabase('default');
    }

    /**
     * Выполняет SQL команды из файла
     */
    public static function executeSql(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("SQL файл не найден: {$filePath}");
        }

        $sql = file_get_contents($filePath);
        if ($sql === false) {
            throw new \RuntimeException("Не удалось прочитать SQL файл: {$filePath}");
        }

        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                \R::exec($statement);
            }
        }
    }
}

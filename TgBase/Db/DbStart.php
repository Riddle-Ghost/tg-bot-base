<?php

namespace TgBase\Db;

require_once __DIR__ . '/rb-sqlite.php';

class DbStart
{
    public static function init(): void
    {
        \R::setup('sqlite:./database.sqlite');
        self::initTables();
        // \R::freeze(true); // RedBean не будет пытаться менять структуру БД
    }

    private static function initTables(): void
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


        // \R::exec("CREATE TABLE IF NOT EXISTS ai_context (
        //     id INTEGER PRIMARY KEY AUTOINCREMENT,
        //     tg_id BIGINT UNIQUE,
        //     context TEXT,
        //     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        //     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        // )");
        // \R::exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON ai_context (tg_id)");
    }
}

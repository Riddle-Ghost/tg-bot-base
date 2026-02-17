<?php

use Riddle\TgBotBase\Db\DbConfig;
use Riddle\TgBotBase\Db\MigrationDto;
use Riddle\TgBotBase\Db\Seed\SeedDto;

class GetTestDbConfig
{
    public function __invoke(): DbConfig
    {
        $dbConfig = new DbConfig(__DIR__ . '/Db');

        if (is_dir($dbConfig->dbDir)) {
            $this->removeDirectory($dbConfig->dbDir);
        }

        mkdir($dbConfig->dbDir, 0777, true);

        $categoriesMigration = new MigrationDto(
            'tech',
            "CREATE TABLE IF NOT EXISTS test_table_tech (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tag VARCHAR(50),
                name VARCHAR(50),
                info TEXT
            )",
            [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_tag ON test_table_tech (tag)",
            ]
        );

        $dbConfig->addMigration($categoriesMigration);

        $categoriesMigration = new MigrationDto(
            'test_db',
            "CREATE TABLE IF NOT EXISTS test_table_test_db (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tag VARCHAR(50),
                name VARCHAR(50),
                info TEXT
            )",
            [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_tag ON test_table_test_db (tag)",
            ]
        );

        $dbConfig->addMigration($categoriesMigration);

        $seedDto = new SeedDto('tech');
        $seedDto->addDirectoryOrFile(__DIR__ . '/dump/');
        $dbConfig->addSeed($seedDto);

        return $dbConfig;
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}

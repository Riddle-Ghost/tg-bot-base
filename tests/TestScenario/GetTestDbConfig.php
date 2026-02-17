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

        $tableTech1 = new MigrationDto(
            "CREATE TABLE IF NOT EXISTS test_table_tech_1 (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tag VARCHAR(50),
                name VARCHAR(50),
                info TEXT
            )",
            [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_tag ON test_table_tech_1 (tag)",
            ]
        );

        $dbConfig->addMigration($tableTech1);

        $tableTech2 = new MigrationDto(
            "CREATE TABLE IF NOT EXISTS test_table_tech_2 (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tag VARCHAR(50),
                name VARCHAR(50),
                info TEXT
            )",
            [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_tag ON test_table_tech_2 (tag)",
            ]
        );

        $dbConfig->addMigration($tableTech2);

        $tableTestDb = new MigrationDto(
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

        $dbConfig->addMigration($tableTestDb);

        $seedDto1 = new SeedDto();
        $seedDto1->addDirectoryOrFile(__DIR__ . '/dump/test.sql');
        $dbConfig->addSeed($seedDto1);

        $seedDto2 = new SeedDto();
        $seedDto2->addDirectoryOrFile(__DIR__ . '/dump');
        $dbConfig->addSeed($seedDto2);

        $seedDto3 = new SeedDto();
        $seedDto3->addDirectoryOrFile(__DIR__ . '/dump/dir');
        $dbConfig->addSeed($seedDto3);

        $seedDto4 = new SeedDto();
        $seedDto4->addDirectoryOrFile(__DIR__ . '/dump/dir/');
        $dbConfig->addSeed($seedDto4);

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

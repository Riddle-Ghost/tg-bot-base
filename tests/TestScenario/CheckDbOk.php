<?php

use Riddle\TgBotBase\Db\DbConfig;
use Riddle\TgBotBase\Db\MigrationDto;
use Riddle\TgBotBase\Log\Db\LogMigration;
use Riddle\TgBotBase\Db\Seed\SeedMigration;
use Riddle\TgBotBase\User\Db\UserMigration;
use Riddle\TgBotBase\Ai\Db\AiContextMigration;

class CheckDbOk
{
    public function __construct(
        private DbConfig $dbConfig
    ) {}

    public function __invoke(): void
    {
        $this->checkDbFiles();
        $this->checkDbTables();
    }

    private function checkDbFiles(): void
    {
        $dbFiles = [
            UserMigration::DB_NAME,
            AiContextMigration::DB_NAME,
            LogMigration::DB_NAME,
            SeedMigration::DB_NAME,
            'tech',
        ];

        // $dbFiles = array_merge($dbFiles, array_map(fn(MigrationDto $migration) => get_class($migration)::DB_NAME, $this->dbConfig->migrations));

        foreach ($dbFiles as $file) {
            $filePath = $this->dbConfig->dbDir . '/' . $file . '.sqlite';
            if (!file_exists($filePath)) {
                throw new \Exception("База данных {$filePath} не найдена");
            }
        }

        echo 'Проверка файлов базы данных по пути ' . $this->dbConfig->dbDir . ' ОК' . PHP_EOL;
    }

    private function checkDbTables(): void
    {
        $dbToTable = [
            UserMigration::DB_NAME => UserMigration::TABLE_NAME,
            AiContextMigration::DB_NAME => AiContextMigration::TABLE_NAME,
            LogMigration::DB_NAME => LogMigration::TABLE_NAME,
            SeedMigration::DB_NAME => SeedMigration::TABLE_NAME,
            'tech' => 'test_table_tech',
            'test_db' => 'test_table_test_db',
        ];

        // $dbToTable = array_merge($dbToTable, array_map(fn(MigrationDto $migration) => [get_class($migration)::DB_NAME => get_class($migration)::TABLE_NAME], $this->dbConfig->migrations));

        foreach ($dbToTable as $db => $table) {
            \R::selectDatabase($db);
            if (!\R::isTableExists($table)) {
                throw new \Exception("Таблица {$table} не найдена в базе данных {$db}");
            }
        }

        echo 'Проверка таблиц ОК' . PHP_EOL;
    }
}

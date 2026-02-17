<?php

use Riddle\TgBotBase\Db\DbConfig;
use Riddle\TgBotBase\Log\Db\LogMigration;
use Riddle\TgBotBase\Db\Seed\SeedMigration;
use Riddle\TgBotBase\User\Db\UserMigration;
use Riddle\TgBotBase\Ai\Db\AiContextMigration;
use Riddle\TgBotBase\Text\Helpers\VarDump;

class CheckDbOk
{
    public function __construct(
        private DbConfig $dbConfig
    ) {}

    public function __invoke(): void
    {
        $this->checkDbFiles();
        $this->checkDbTables();
        $this->checkSeeds();
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

        foreach ($dbFiles as $file) {
            $filePath = $this->dbConfig->dbDir . '/' . $file . '.sqlite';
            if (!file_exists($filePath)) {
                VarDump::error("База данных {$filePath} не найдена");
                die;
            }
        }

        VarDump::success('Проверка файлов базы данных по пути ' . $this->dbConfig->dbDir . ' ОК');
    }

    private function checkDbTables(): void
    {
        $dbToTable = [
            UserMigration::DB_NAME => UserMigration::TABLE_NAME,
            AiContextMigration::DB_NAME => AiContextMigration::TABLE_NAME,
            LogMigration::DB_NAME => LogMigration::TABLE_NAME,
            SeedMigration::DB_NAME => SeedMigration::TABLE_NAME,
            'tech' => 'test_table_tech_1',
            'tech' => 'test_table_tech_2',
            'test_db' => 'test_table_test_db',
        ];

        foreach ($dbToTable as $db => $table) {
            \R::selectDatabase($db);
            $tables = \R::inspect();
            
            if (!in_array($table, $tables)) {
                VarDump::error("Таблица {$table} не найдена в базе данных {$db}");
                die;
            }
        }

        VarDump::success('Проверка таблиц ОК');
    }

    private function checkSeeds(): void
    {
        $this->testSeedsInTable('tech', 'test_table_tech_1', 10);
        $this->testSeedsInTable('tech', 'test_table_tech_2', 10);
        $this->testSeedsInTable('test_db', 'test_table_test_db', 10);
        $this->testSeedsInTable('tech', 'seeds', 3);

        VarDump::success('Проверка сидов ОК');
    }

    private function testSeedsInTable(string $db, string $table, int $expectedCount): void
    {
        \R::selectDatabase($db);
        $count = \R::count($table);

        if ($count !== $expectedCount) {
            VarDump::error("Таблица {$table} содержит {$count} строк");
            die;
        }
    }
}

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
            'default',
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
            'default' => UserMigration::TABLE_NAME,
            'default' => AiContextMigration::TABLE_NAME,
            'default' => LogMigration::TABLE_NAME,
            'default' => SeedMigration::TABLE_NAME,
            'default' => 'test_table_tech_1',
            'default' => 'test_table_tech_2',
            'default' => 'test_table_test_db',
        ];

        foreach ($dbToTable as $db => $table) {
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
        $this->testSeedsInTable('test_table_tech_1', 10);
        $this->testSeedsInTable('test_table_tech_2', 10);
        $this->testSeedsInTable('test_table_test_db', 10);
        $this->testSeedsInTable('seeds', 3);

        VarDump::success('Проверка сидов ОК');
    }

    private function testSeedsInTable(string $table, int $expectedCount): void
    {
        $count = \R::count($table);

        if ($count !== $expectedCount) {
            VarDump::error("Таблица {$table} содержит {$count} строк");
            die;
        }
    }
}

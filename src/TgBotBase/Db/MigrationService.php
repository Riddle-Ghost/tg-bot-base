<?php

namespace Riddle\TgBotBase\Db;

use Riddle\TgBotBase\Ai\Db\AiContextMigration;
use Riddle\TgBotBase\Log\Db\LogMigration;
use Riddle\TgBotBase\Db\Seed\SeedMigration;
use Riddle\TgBotBase\User\Db\UserMigration;

require_once __DIR__ . '/rb-sqlite.php';

class MigrationService
{
    public const string DEFAULT_DB_NAME = 'default';

    public function __construct(
        public readonly DbConfig $config,
    ) {}

    public function migrateAll(): void
    {
        \R::setup($this->getDsn(self::DEFAULT_DB_NAME));
        $this->migrate(new UserMigration());
        $this->migrate(new AiContextMigration());
        $this->migrate(new LogMigration());
        $this->migrate(new SeedMigration());
        
        foreach ($this->config->migrations as $migration) {
            $this->migrate($migration);
        }
    }

    public function migrate(MigrationDto $dto): void
    {
        $result = \R::exec($dto->createTableSql);
        foreach ($dto->indexSql as $indexSql) {
            $result = \R::exec($indexSql);
        }
    }

    public function getDsn(): string
    {
        return 'sqlite:' . $this->databaseFilePath();
    }

    public function databaseFilePath(): string
    {
        return $this->config->dbDir . '/' . self::DEFAULT_DB_NAME . '.sqlite';
    }
}

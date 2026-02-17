<?php

namespace Riddle\TgBotBase\Db;

use Riddle\TgBotBase\Ai\Db\AiContextMigration;
use Riddle\TgBotBase\Log\Db\LogMigration;
use Riddle\TgBotBase\Db\Seed\SeedMigration;
use Riddle\TgBotBase\User\Db\UserMigration;

require_once __DIR__ . '/rb-sqlite.php';

class MigrationService
{
    public function __construct(
        public readonly DbConfig $config,
    ) {}

    public function migrateAll(): void
    {
        \R::setup($this->getDsn(UserMigration::DB_NAME));
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
        if (!\R::hasDatabase($dto->dbName)) {
            \R::addDatabase($dto->dbName, self::getDsn($dto->dbName));
        }

        \R::selectDatabase($dto->dbName);
        $result = \R::exec($dto->createTableSql);
        foreach ($dto->indexSql as $indexSql) {
            $result = \R::exec($indexSql);
        }
    }

    public function getDsn(string $dbName): string
    {
        return 'sqlite:' . $this->getPath($dbName);
    }

    public function getPath(string $dbName): string
    {
        return $this->config->dbDir . '/' . $dbName . '.sqlite';
    }
}

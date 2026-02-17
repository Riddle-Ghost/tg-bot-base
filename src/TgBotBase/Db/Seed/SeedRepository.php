<?php

namespace Riddle\TgBotBase\Db\Seed;

use Riddle\TgBotBase\Db\BaseRepository;

require_once __DIR__ . '/../rb-sqlite.php';

class SeedRepository extends BaseRepository
{
    public function getDb(): string
    {
        return SeedMigration::DB_NAME;
    }

    public function getTable(): string
    {
        return SeedMigration::TABLE_NAME;
    }

    public function getCount(string $filePath): int
    {
        $this->switchDb();

        return \R::count($this->getTable(), 'file = ?', [$filePath]);
    }

    public function insert(string $filePath): void
    {
        $this->switchDb();

        \R::exec("INSERT INTO " . $this->getTable() . " (file) VALUES (?)", [$filePath]);
    }
}

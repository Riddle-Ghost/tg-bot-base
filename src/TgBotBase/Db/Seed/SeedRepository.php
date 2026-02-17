<?php

namespace Riddle\TgBotBase\Db\Seed;

require_once __DIR__ . '/../rb-sqlite.php';

class SeedRepository
{
    public function getTable(): string
    {
        return SeedMigration::TABLE_NAME;
    }

    public function getCount(string $filePath): int
    {
        return \R::count($this->getTable(), 'file = ?', [$filePath]);
    }

    public function insert(string $filePath): void
    {
        $result = \R::exec("INSERT INTO " . $this->getTable() . " (file) VALUES (?)", [$filePath]);
        if (!$result) {
            throw new \RuntimeException("Не удалось добавить сид");
        }
    }
}

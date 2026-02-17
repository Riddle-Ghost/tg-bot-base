<?php

namespace Riddle\TgBotBase\Db;

use Riddle\TgBotBase\Db\Seed\SeedDto;

class DbConfig
{
    public function __construct(
        public readonly string $dbDir,
        public private(set) array $migrations = [],
        public private(set) array $seeds = [],
    ) {}

    /**
     * Можно добавлять новые таблицы и индексы
     */
    public function addMigration(MigrationDto $dto): self
    {
        $this->migrations[] = $dto;

        return $this;
    }

    /**
     * Можно заполнять таблицы
     */
    public function addSeed(SeedDto $dto): self
    {
        $this->seeds[] = $dto;

        return $this;
    }
}

<?php

namespace Riddle\TgBotBase\Db;

class MigrationDto
{
    public function __construct(
        public readonly string $createTableSql,
        public readonly array $indexSql,
    ) {}
}

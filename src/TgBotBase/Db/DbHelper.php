<?php

namespace Riddle\TgBotBase\Db;

use Riddle\TgBotBase\Db\Seed\SeedService;
use Riddle\TgBotBase\Db\Seed\SeedRepository;

require_once __DIR__ . '/rb-sqlite.php';

class DbHelper
{
    public static function init(DbConfig $config): void
    {
        $migrationService = new MigrationService($config);
        $migrationService->migrateAll();

        $seedService = new SeedService($config, new SeedRepository());
        $seedService->seedAll();

        \R::freeze(true); // RedBean не будет пытаться менять структуру БД
    }
}

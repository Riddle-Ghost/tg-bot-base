<?php

require __DIR__.'/vendor/autoload.php';

use TgBase\TgBot;
use Dotenv\Dotenv;
use TgBase\Db\DbStart;
use TgBase\TgBotConfig;

if (file_exists(__DIR__ . '/.env')) {
    Dotenv::createImmutable(__DIR__)->safeLoad();
}

DbStart::init();

$token = $_ENV['TG_BOT_TOKEN'] ?? getenv('TG_BOT_TOKEN') ?? null;
if (!$token) {
    throw new RuntimeException('Missing TG_BOT_TOKEN in environment.');
}

$config = new TgBotConfig($token);
$tgbot = new TgBot(new TgBotHandler(), $config);
$tgbot->run();

echo "OK";

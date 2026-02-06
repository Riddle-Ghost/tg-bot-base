<?php

namespace Riddle\TgBotBase\Log\Helpers;

use Riddle\TgBotBase\Log\Db\LogRepository;

class LogHelper
{
    public static function aiRequest(string $text, int $userId): void
    {
        self::save(LogRepository::TYPE_AI_REQUEST, $text, $userId);
    }

    public static function save(string $type, string $text, ?int $userId = null): void
    {
        (new LogRepository())->save($type, $text, $userId);
    }
}

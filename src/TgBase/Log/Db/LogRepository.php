<?php

namespace Riddle\TgBase\Log\Db;

class LogRepository
{
    public const TYPE_AI_REQUEST = 'ai_request';

    public function save(string $type, string $text, ?int $userId = null): void
    {
        $bean = \R::dispense('log');
        $bean->type = $type;
        $bean->user_id = $userId;
        $bean->text = $text;

        \R::store($bean);
    }
}

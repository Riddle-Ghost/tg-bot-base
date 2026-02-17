<?php

namespace Riddle\TgBotBase\Log\Db;

class LogRepository
{
    public const TYPE_AI_REQUEST = 'ai_request';

    protected function getTable(): string
    {
        return LogMigration::TABLE_NAME;
    }

    public function save(string $type, string $text, ?int $userId = null): void
    {
        $bean = \R::dispense($this->getTable());
        $bean->type = $type;
        $bean->user_id = $userId;
        $bean->text = $text;

        \R::store($bean);
    }
}

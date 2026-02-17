<?php

namespace Riddle\TgBotBase\Ai\Db;

use Riddle\TgBotBase\Ai\Entity\AiContext;

class AiContextRepository
{
    public function getTable(): string
    {
        return AiContextMigration::TABLE_NAME;
    }

    public function getByTgId(int $tgId): AiContext
    {
        $bean = $this->getModel($tgId);

        $context = $bean && $bean->context ? json_decode($bean->context, true) : [];

        $dto = new AiContext($tgId, $context);

        return $dto;
    }

    /**
     * Сохранить или обновить контекст
     */
    public function save(AiContext $dto): void
    {
        $bean = $this->getModel($dto->tgId);

        if (!$bean) {
            $bean = \R::dispense($this->getTable());
            $bean->tg_id = $dto->tgId;
        }

        $bean->context = json_encode(
            $dto->context, 
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

        \R::store($bean);
    }

    private function getModel(int $tgId): ?\RedBeanPHP\OODBBean
    {
        return \R::findOne($this->getTable(), 'tg_id = ?', [$tgId]);
    }
}
<?php

namespace Riddle\TgBotBase\Ai\Db;

use Riddle\TgBotBase\Ai\Entity\AiContext;
use Riddle\TgBotBase\Db\BaseRepository;

class AiContextRepository extends BaseRepository
{
    public function getDb(): string
    {
        return AiContextMigration::DB_NAME;
    }

    public function getTable(): string
    {
        return AiContextMigration::TABLE_NAME;
    }

    public function getByTgId(int $tgId): ?AiContext
    {
        $bean = $this->getModel($tgId);

        // Если запись не найдена или поле context пустое
        if (!$bean || empty($bean->context)) {
            return null;
        }

        $dto = new AiContext($tgId);
        
        // Декодируем JSON из базы в массив PHP
        $decodedContext = json_decode($bean->context, true);
        
        // Проверяем, что это массив, и записываем в DTO
        $dto->context = is_array($decodedContext) ? $decodedContext : [];

        return $dto;
    }

    /**
     * Сохранить или обновить контекст
     */
    public function save(AiContext $dto): void
    {
        $bean = $this->getModel($dto->tgId);

        // Если записи нет, создаем новую "бину" (bean)
        if (!$bean) {
            $bean = \R::dispense($this->getTable());
            $bean->tg_id = $dto->tgId;
        }

        // Кодируем массив в JSON. 
        // JSON_UNESCAPED_UNICODE — чтобы кириллица не превращалась в \u0430
        // JSON_PRETTY_PRINT — для читаемости (аналог indent=2)
        $bean->context = json_encode(
            $dto->getContext(), 
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

        \R::store($bean);
    }

    private function getModel(int $tgId): ?\RedBeanPHP\OODBBean
    {
        $this->switchDb();

        return \R::findOne($this->getTable(), 'tg_id = ?', [$tgId]);
    }
}
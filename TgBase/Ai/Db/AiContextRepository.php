<?php

namespace TgBase\Ai\Db;

use TgBase\Ai\Entity\AiContext;

class AiContextRepository
{
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
            $bean = \R::dispense('aicontext');
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
        // Поиск по полю tg_id
        return \R::findOne('aicontext', 'tg_id = ?', [$tgId]);
    }
}
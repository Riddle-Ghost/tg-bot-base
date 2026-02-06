<?php

namespace Riddle\TgBase\User\Db;

use Riddle\TgBase\User\Entity\User;
use Riddle\TgBase\User\Entity\Settings;

class UserRepository
{
    public function getByTgId(int $tgId): ?User
    {
        $bean = $this->getModel($tgId);

        if (!$bean) {
            return null;
        }

        return new User(
            id: $bean->id,
            tgId: $bean->tg_id,
            username: $bean->username,
            isPremium: $bean->is_premium,
            isBlocked: $bean->is_blocked,
            settings: Settings::fromDict(json_decode($bean->settings ?? '{}', true))
        );
    }

    public function save(User $entity): void
    {
        $bean = $this->getModel($entity->tgId);

        // Если записи нет, создаем новую "бину" (bean)
        if (!$bean) {
            $bean = \R::dispense('user');
            $bean->tg_id = $entity->tgId;
        }

        $bean->username = $entity->username;
        $bean->is_premium = $entity->isPremium;
        $bean->is_blocked = $entity->isBlocked;
        $bean->settings = json_encode($entity->settings->toDict());

        \R::store($bean);
    }

    private function getModel(int $tgId): ?\RedBeanPHP\OODBBean
    {
        return \R::findOne('user', 'tg_id = ?', [$tgId]);
    }
}
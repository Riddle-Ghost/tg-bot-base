<?php

namespace Riddle\TgBase\User\Entity;

use Riddle\TgBase\User\Entity\Settings;

class User
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $tgId,
        public readonly ?string $username,
        public readonly bool $isPremium,
        public readonly bool $isBlocked,
        public readonly Settings $settings,
    ) {}

    public function block(): self
    {
        if ($this->isBlocked) {
            throw new \Exception('User is already blocked');
        }
        $this->isBlocked = true;

        return $this;
    }
    
    public function unblock(): self
    {
        if (!$this->isBlocked) {
            throw new \Exception('User is not blocked');
        }
        $this->isBlocked = false;

        return $this;
    }
}

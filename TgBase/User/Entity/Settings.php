<?php

namespace TgBase\User\Entity;

class Settings
{
    public function __construct(
        public ?int $keyboardRow = null,
    ) {}

    public function toDict(): array
    {
        return [
            'keyboard_row' => $this->keyboardRow
        ];
    }
    
    public static function fromDict(array $data): self
    {
        return new self(
            $data['keyboard_row'] ?? null
        );
    }
}

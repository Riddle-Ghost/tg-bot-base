<?php

namespace TgBase\User\Entity;

class Settings
{
    public function __construct(
        public ?int $rowSize = null,
    ) {}

    public function toDict(): array
    {
        return [
            'row_size' => $this->rowSize
        ];
    }
    
    public static function fromDict(array $data): self
    {
        return new self(
            $data['row_size'] ?? null
        );
    }
}

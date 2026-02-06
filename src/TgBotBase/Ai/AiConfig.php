<?php

namespace Riddle\TgBotBase\Ai;

class AiConfig
{
    public function __construct(
        public readonly string $url,
        public readonly string $token,
        public readonly string $model,
    ) {}
}
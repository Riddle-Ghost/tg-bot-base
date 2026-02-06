<?php

namespace Riddle\TgBotBase;

class TgBotConfig
{
    public function __construct(
        public readonly string $tgBotToken
    ) {}
}

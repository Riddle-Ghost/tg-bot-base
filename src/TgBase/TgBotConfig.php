<?php

namespace Riddle\TgBase;

class TgBotConfig
{
    public function __construct(
        public readonly string $tgBotToken
    ) {}
}

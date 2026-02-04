<?php

namespace TgBase;

use Telegram\Bot\Keyboard\Keyboard;

class Output
{
    public const MARKDOWN = "Markdown";
    public const HTML = "HTML";

    public function __construct(
        public readonly string $text,
        public readonly ?Keyboard $keyboard = null,
        public readonly string $parseMode = self::HTML
    ) {}
}

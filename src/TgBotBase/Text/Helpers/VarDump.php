<?php

namespace Riddle\TgBotBase\Text\Helpers;

class VarDump
{
    // Цвета текста
    public const BLACK = "\033[30m";
    public const RED = "\033[31m";
    public const GREEN = "\033[32m";
    public const YELLOW = "\033[33m";
    public const BLUE = "\033[34m";
    public const MAGENTA = "\033[35m";
    public const WHITE = "\033[37m";
    public const BRIGHT_RED = "\033[91m";
    public const BRIGHT_BLUE = "\033[94m";
    public const BRIGHT_MAGENTA = "\033[95m";
    public const RESET = "\033[0m";

    // Стили
    public const BOLD = "\033[1m";
    public const UNDERLINE = "\033[4m";
    public const BLINK = "\033[5m";
    public const INVERSE = "\033[7m";
    public const ITALIC = "\033[3m";

    public static function info(string $text): void
    {
        self::dd($text, self::BLACK, self::INVERSE);
    }

    public static function warning(string $text): void
    {
        self::dd($text, self::YELLOW, self::INVERSE);
    }

    public static function error(string $text): void
    {
        self::dd($text, self::RED, self::INVERSE);
    }

    public static function success(string $text): void
    {
        self::dd($text, self::GREEN, self::INVERSE);
    }

    public static function dd(string $text, ?string $color = null, ?string $style = null): void
    {
        $output = "";

        if ($style !== null) {
            $output .= $style;
        }
        if ($color !== null) {
            $output .= $color;
        }

        $output .= $text . self::RESET;

        echo $output . PHP_EOL;
    }
}
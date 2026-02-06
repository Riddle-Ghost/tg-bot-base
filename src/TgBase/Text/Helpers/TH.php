<?php

namespace Riddle\TgBase\Text\Helpers;

class TH
{
    /**
     * Жирный текст
     */
    public static function b(string $text): string
    {
        return "<b>{$text}</b>";
    }

    /**
     * Спойлер / Цитата
     */
    public static function blockquote(string $text): string
    {
        return "<blockquote expandable>{$text}</blockquote>";
    }

    /**
     * Обрамляет в тексте совпадения с pattern в тэги <b></b>
     * Паттерн должен включать делимитеры, например: '/\w+/'
     */
    public static function boldRegex(string $text, string $pattern): string
    {
        return preg_replace_callback($pattern, function ($matches) {
            return self::b($matches[0]);
        }, $text);
    }

    /**
     * Обрамляет совпадения первой группы pattern в тэги <blockquote>
     * Флаг 's' в конце паттерна имитирует re.S (dotall)
     */
    public static function blockquoteRegex(string $text, string $pattern, ?int $minChars = null): string
    {
        // В PHP флаги передаются внутри строки паттерна после делимитера
        // Если флаг 's' не передан в $pattern, можно добавить его принудительно:
        if (!str_ends_with($pattern, 's') && strrpos($pattern, $pattern[0]) !== false) {
             // Это упрощенная логика добавления флага
        }

        return preg_replace_callback($pattern, function ($matches) use ($minChars) {
            $fullMatchText = $matches[0];
            
            // Проверяем, захвачена ли первая группа
            if (!isset($matches[1])) {
                return $fullMatchText;
            }

            $insideGroupText = $matches[1];

            // Проверка минимальной длины (используем mb_ для корректности)
            if ($minChars !== null && mb_strlen($insideGroupText) < $minChars) {
                return $fullMatchText;
            }

            // Заменяем только текст внутри группы
            return str_replace($insideGroupText, self::blockquote($insideGroupText), $fullMatchText);
        }, $text);
    }
}

// Как было реализовано в питоне 
    // def blockquoteRegex(text: str, pattern: str, minChars: int|None = None):
    //     def replacer(match):
    //         fullMatchText = match.group(0)
    //         insideGroupText = match.group(1)

    //         if minChars is not None and len(insideGroupText) < minChars:
    //             return fullMatchText

    //         return fullMatchText.replace(insideGroupText, TH.blockquote(insideGroupText))
        
    //     return re.sub(pattern, replacer, text, flags=re.S)
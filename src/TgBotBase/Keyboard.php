<?php

namespace Riddle\TgBotBase;

use Telegram\Bot\Keyboard\Keyboard as TelegramKeyboard;
use Telegram\Bot\Keyboard\Button;

class Keyboard
{
    public const CURRENT_BUTTON = '✅';
    public const DEFAULT_ROW_SIZE = 4;

    private TelegramKeyboard $keyboard;

    public function __construct()
    {
        $this->keyboard = TelegramKeyboard::make()
            ->inline();
    }

    public static function button(string $text, string $callbackData): Button
    {
        return TelegramKeyboard::inlineButton(['text' => $text, 'callback_data' => $callbackData]);
    }

    public static function buttonToStart(): Button
    {
        return self::button(text: 'К началу', callbackData: '/start');
    }

    /**
     * @param Button[] $buttons
     */
    public function addRows(array $buttons, ?int $rowSize = self::DEFAULT_ROW_SIZE): self
    {
        while (count($buttons) > $rowSize) {
            $subset = array_splice($buttons, 0, $rowSize);
            $this->addRow($subset);
        }

        $this->addRow($buttons);
        return $this;
    }

    /**
     * @param Button[] $buttons
     */
    public function addRow(array $buttons): self
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        $this->keyboard->row($row);

        return $this;
    }

    public function generate(): TelegramKeyboard
    {
        return $this->keyboard;
    }
}

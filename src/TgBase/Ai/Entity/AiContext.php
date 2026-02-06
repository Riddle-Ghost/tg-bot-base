<?php

namespace Riddle\TgBase\Ai\Entity;

class AiContext
{
    public const ROLE_SYSTEM = "system";
    public const ROLE_USER = "user";
    public const ROLE_ASSISTANT = "assistant";

    public ?int $tgId = null;
    /** @var array<array{role: string, content: string}> */
    public array $context = [];

    public function __construct(?int $tgId = null)
    {
        $this->tgId = $tgId;
    }

    public function addUser(string $content): void
    {
        $this->context[] = [
            "role" => self::ROLE_USER,
            "content" => $content
        ];
    }

    public function addAssistant(string $content): void
    {
        $this->context[] = [
            "role" => self::ROLE_ASSISTANT,
            "content" => $content
        ];
    }

    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Вычисляет общее количество символов в контексте
     */
    private function getCharCount(): int
    {
        return array_reduce($this->context, function (int $carry, array $msg) {
            return $carry + mb_strlen($msg['content']);
        }, 0);
    }

    public function trimContext(int $maxChars): void
    {
        // Пока количество символов больше лимита
        while ($this->getCharCount() > $maxChars) {
            $oldestUserMsgIndex = null;

            // Ищем первое (самое старое) сообщение, которое не является системным
            foreach ($this->context as $index => $msg) {
                if ($msg['role'] !== self::ROLE_SYSTEM) {
                    $oldestUserMsgIndex = $index;
                    break;
                }
            }

            // Если найдено сообщение для удаления
            if ($oldestUserMsgIndex !== null) {
                // array_splice удаляет элемент и пересчитывает индексы массива
                array_splice($this->context, $oldestUserMsgIndex, 1);
            } else {
                // Если остались только системные сообщения, прерываем цикл
                break;
            }
        }
    }
}
<?php

namespace Riddle\TgBotBase\BotCore;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use Riddle\TgBotBase\Db\DbHelper;
use Riddle\TgBotBase\User\Entity\User;
use Riddle\TgBotBase\BotCore\Dto\Input;
use Riddle\TgBotBase\BotCore\Dto\Output;
use Riddle\TgBotBase\BotCore\TgBotConfig;
use Riddle\TgBotBase\User\Entity\Settings;
use Riddle\TgBotBase\User\Db\UserRepository;

class TgBot
{
    private Api $api;
    private UserRepository $userRepository;

    public function __construct(
        private TgBotHandlerInterface $tgBotHandler,
        private TgBotConfig $tgBotConfig
    )
    {
        DbHelper::init();
        $this->api = new Api($this->tgBotConfig->tgBotToken);
        $this->api->deleteWebhook();
        $this->addCommands();
        $this->userRepository = new UserRepository();
    }

    public function run()
    {
        $updates = $this->getUpdates();

        while (count($updates) > 0) {
            foreach ($updates as $update) {

                $output = $this->handleEvent($update);

                $chatId = $update->getChat()->getId();

                if ($output) {
                    $response = $this->api->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $output->text,
                        'reply_markup' => $output->keyboard
                    ]);
                }
            }

            $offset = $update->getUpdateId() + 1;
            $updates = $this->getUpdates($offset);
        }
    }

    /**
     * @return Update[]
     */
    private function getUpdates(?int $offset = null): array
    {
        $updates = $this->api->getUpdates([
            'timeout' => 5, //Check for new messages every ... seconds
            'offset' => $offset,
        ]);

        return $updates;
    }

    private function handleEvent(Update $update): ?Output
    {
        $user = $this->getUser($update);
        $relatedObject = $update->getRelatedObject();

        if ($update->isType('callback_query')) {

            $input = new Input(
               $relatedObject->getData(),
               Input::TYPE_BUTTON,
               $user,
            );
           
            return $this->tgBotHandler->handleButton($input);
        }

        if ($update->isType('message')) {

            if ($relatedObject->hasCommand()) {

                $input = new Input(
                    $relatedObject->getText(),
                    Input::TYPE_COMMAND,
                    $user,
                );


                if ($relatedObject->getText() === '/start') {
                    return $this->tgBotHandler->handleStart($input);
                }

                return $this->tgBotHandler->handleCommand($input);
            }

            if ($relatedObject->isType('text')) {
                $input = new Input(
                    $relatedObject->getText(),
                    Input::TYPE_MESSAGE,
                    $user,
                );

                return $this->tgBotHandler->handleMessage($input);
            }
        }

        return null;
    }

    private function addCommands(): void
    {
        $commands = [
            ['command' => 'start', 'description' => 'Запуск'],
            ['command' => 'help', 'description' => 'Помощь'],
        ];
        $this->api->setMyCommands($commands);


        //     # Получаем и выводим актуальный список команд
        //     actualCommands = await self.bot.get_my_commands()
        //     VarDump.info("Список команд:")
        //     for cmd in actualCommands:
        //     VarDump.dd(f" - /{cmd.command}: {cmd.description}")
    }

    private function getUser(Update $update): User
    {
        $chat = $update->getRelatedObject()->getChat()
            ? $update->getRelatedObject()->getChat()
            : $update->getRelatedObject()->getMessage()->getChat();

        $user = $this->userRepository->getByTgId($chat->getId());
        if (!$user) {
            $user = new User(
                id: null,
                tgId: $chat->getId(),
                username: $chat->getUsername(),
                isPremium: false,
                isBlocked: false,
                settings: new Settings()
            );
            $this->userRepository->save($user);
        }

        return $user;
    }
}

<?php

namespace TgBase;

use TgBase\Output;
use Telegram\Bot\Api;
use TgBase\TgBotConfig;
use Telegram\Bot\Objects\Update;
use TgBase\User\Db\UserRepository;
use TgBase\User\Entity\Settings;
use TgBase\User\Entity\User;

class TgBot
{
    private Api $api;
    private UserRepository $userRepository;

    public function __construct(
        private TgBotHandlerInterface $tgBotHandler,
        private TgBotConfig $tgBotConfig
    )
    {
        $this->api = new Api($this->tgBotConfig->tgBotToken);
        $this->api->deleteWebhook();
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

    private function getUser(Update $update): User
    {
        $tgId = $update->getRelatedObject()->getChat()
            ? $update->getRelatedObject()->getChat()->getId()
            : $update->getRelatedObject()->getMessage()->getChat()->getId();

        $user = $this->userRepository->getByTgId($tgId);
        if (!$user) {
            $user = new User(
                id: null,
                tgId: $tgId,
                username: $update->getRelatedObject()->getChat()->getUsername(),
                isPremium: false,
                isBlocked: false,
                settings: new Settings()
            );
            $this->userRepository->save($user);
        }

        return $user;
    }
}

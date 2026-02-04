<?php

use TgBase\Input;
use TgBase\Output;
use TgBase\Keyboard;
use TgBase\Ai\AiService;
use TgBase\Ai\Entity\AiContext;
use TgBase\TgBotHandlerInterface;

class TgBotHandler implements TgBotHandlerInterface
{

    public function commands()
    {

    }

    public function handleStart(Input $input): Output
    {
        return new Output('Start');
    }

    public function handleButton(Input $input): Output
    {
        return new Output('Button: ' . $input->text);
    }

    public function handleMessage(Input $input): Output
    {
        $aiContext = new AiContext();
        $aiContext->tgId = $input->user->tgId;
        $aiContext->addUser($input->text);
        $text = (new AiService(new OpenaiPromptAPI()))->request($aiContext);
        return new Output($text);

        $keyboard = (new Keyboard())
            ->addRows([
                Keyboard::button('12425', 'btn_1'),
                Keyboard::button('2', 'btn_2'),
                Keyboard::button('3', 'btn_3'),
                Keyboard::button('4', 'btn_4'),
                Keyboard::button('5', 'btn_5'),
                Keyboard::button('6', 'btn_6'),
                Keyboard::button('7', 'btn_7'),
                Keyboard::buttonToStart(),
            ])
            ->generate();

        return new Output('Message: ' . $input->text, $keyboard);
    }

    public function handleCommand(Input $input): Output
    {
        return new Output('Command: ' . $input->text);
    }
}

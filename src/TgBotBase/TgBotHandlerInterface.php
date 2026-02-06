<?php

namespace Riddle\TgBotBase;

interface TgBotHandlerInterface
{
    public function handleStart(Input $input): Output;

    public function handleButton(Input $input): Output;

    public function handleMessage(Input $input): Output;

    public function handleCommand(Input $input): Output;
}

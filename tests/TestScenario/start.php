<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/TestTgBotHandler.php';
require __DIR__.'/GetTestDbConfig.php';

use Riddle\TgBotBase\Ai\AiService;
use Riddle\TgBotBase\BotCore\TgBot;
use Riddle\TgBotBase\BotCore\TgBotConfig;
use Riddle\TgBotBase\Ai\Api\OpenaiPromptAPI;
use Riddle\TgBotBase\Ai\AiServiceLogDecorator;
use Riddle\TgBotBase\Ai\AiServiceContextDecorator;

//==================
$tgBotToken = '';
$openaiApiKey = '';
$openaiPromptId = '';
//==================

$dbConfig = (new GetTestDbConfig())();
$tgBotConfig = new TgBotConfig($tgBotToken, $dbConfig);

$api = new OpenaiPromptAPI(
    $openaiApiKey,
    $openaiPromptId,
);
$aiService = new AiService($api);
$aiService = new AiServiceContextDecorator($aiService, 1500);
$aiService = new AiServiceLogDecorator($aiService);
        
$tgBot = new TgBot(new TestTgBotHandler($aiService), $tgBotConfig);
$tgBot->run();

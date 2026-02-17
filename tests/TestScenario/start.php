<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/TestTgBotHandler.php';
require __DIR__.'/GetTestDbConfig.php';
require __DIR__.'/CheckDbOk.php';

use Riddle\TgBotBase\Ai\AiService;
use Riddle\TgBotBase\BotCore\TgBot;
use Riddle\TgBotBase\Ai\Api\BaseApi;
use Riddle\TgBotBase\BotCore\TgBotConfig;
use Riddle\TgBotBase\Ai\Api\OpenaiPromptAPI;
use Riddle\TgBotBase\Ai\AiServiceLogDecorator;
use Riddle\TgBotBase\Ai\AiServiceContextDecorator;

//==================
# Telegram Bot Configuration
$tgBotToken = '';

# OpenAI API Configuration
$openaiApiKey = '';
$openaiPromptId = '';

# OpenRouter API Configuration
$openrouterApiKey = '';
$openrouterApiUrl = 'https://openrouter.ai/api/v1/chat/completions';
$openrouterModel = 'deepseek/deepseek-r1-0528:free';

# Gemini API Configuration
$geminiApiKey = '';
$geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/chat/completions';
$geminiModel = 'gemini-flash-latest';
//==================


$dbConfig = (new GetTestDbConfig())();
$tgBotConfig = new TgBotConfig($tgBotToken, $dbConfig);

$openaiPromptAPI = new OpenaiPromptAPI(
    token: $openaiApiKey,
    promptId: $openaiPromptId,
);
$openrouterAPI = new BaseApi(
    token: $openrouterApiKey,
    url: $openrouterApiUrl,
    model: $openrouterModel,
);
$geminiAPI = new BaseApi(
    token: $geminiApiKey,
    url: $geminiApiUrl,
    model: $geminiModel,
);

$aiService = new AiService($geminiAPI);
$aiService = new AiServiceContextDecorator($aiService, 1500);
$aiService = new AiServiceLogDecorator($aiService);
        
$tgBot = new TgBot(new TestTgBotHandler($aiService), $tgBotConfig);
(new CheckDbOk($dbConfig))();
$tgBot->run();

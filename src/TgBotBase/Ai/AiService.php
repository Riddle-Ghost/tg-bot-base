<?php

namespace Riddle\TgBotBase\Ai;

use Riddle\TgBotBase\Ai\Entity\AiContext;
use Riddle\TgBotBase\Ai\Api\BaseApi;

class AiService
{
    private BaseApi $api;

    public function __construct(BaseApi $api)
    {
        $this->api = $api;
    }

    public function request(AiContext $aiContext): string
    {
        $responseText = $this->api->request($aiContext);

        return $responseText;
    }
}

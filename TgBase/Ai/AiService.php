<?php

namespace TgBase\Ai;

use TgBase\Ai\Entity\AiContext;
use TgBase\Ai\Api\BaseApi;

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

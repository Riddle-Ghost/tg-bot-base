<?php

namespace Riddle\TgBase\Ai\Api;

use Riddle\TgBase\Ai\AiConfig;
use RuntimeException;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Riddle\TgBase\Ai\Entity\AiContext;
use GuzzleHttp\Exception\GuzzleException;

class OpenaiPromptAPI extends BaseApi
{
    private Client $client;

    public function __construct(
        protected string $token,
        protected string $promptId,
    )
    {
        $this->client = new OpenAI(api_key=self.token)
    }


    /**
     * @throws RuntimeException|GuzzleException
     */
    public function request(AiContext $aiContext): string
    {
        // response = self.client.responses.create(
        //     prompt={
        //         "id": self.promptId,
        //     },
        //     input=aiContextDto.getContext()
        // )
    }
}
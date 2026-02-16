<?php

namespace Riddle\TgBotBase\Ai\Api;

use RuntimeException;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Riddle\TgBotBase\Ai\AiConfig;
use GuzzleHttp\Exception\GuzzleException;
use Riddle\TgBotBase\Ai\Entity\AiContext;

class BaseApi implements ApiInterface
{
    private Client $client;

    public function __construct(
        public readonly string $url,
        public readonly string $token,
        public readonly string $model,
    )
    {
        $this->client = new Client();
    }

    /**
     * Выполнение запроса к API ИИ (OpenAI | Openrouter | etc)
     * @throws RuntimeException|GuzzleException
     */
    public function request(AiContext $aiContext): string
    {
        try {
            $response = $this->client->post($this->url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json',
                ],
                // Опция 'json' автоматически кодирует массив в JSON 
                // и ставит заголовок Content-Type: application/json
                'json' => [
                    'model'    => $this->model,
                    'messages' => $aiContext->getContext(),
                ],
                'timeout' => 30,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['choices'][0]['message']['content'])) {
                throw new RuntimeException("Invalid response structure from AI API");
            }

            return $data['choices'][0]['message']['content'];

        } catch (GuzzleException $e) {
            // Guzzle ловит и ошибки соединения, и ответы 4xx/5xx
            throw new RuntimeException("AI API Request failed: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
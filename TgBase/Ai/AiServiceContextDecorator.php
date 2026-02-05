<?php

namespace TgBase\Ai;

use TgBase\Ai\Entity\AiContext;
use TgBase\Ai\Db\AiContextRepository;

class AiServiceContextDecorator extends AiService
{
    private AiService $aiService;
    private AiContextRepository $aiContextRepository;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
        $this->aiContextRepository = new AiContextRepository();
    }

    public function request(AiContext $aiContext): string
    {
        $aiContext = $this->withAllContext($aiContext);
        $aiContext->trimContext(5000);

        $responseText = $this->aiService->request($aiContext);

        $aiContext->addAssistant($responseText);
        $this->saveContext($aiContext);

        // LogHelper::aiRequest($aiContext->getContext(), $aiContext->userId);

        return $responseText;
    }

    public function saveContext(AiContext $aiContext): void
    {
        $this->aiContextRepository->save($aiContext);
    }

    public function withAllContext(AiContext $aiContext): AiContext
    {
        $dto = $this->aiContextRepository->getByTgId($aiContext->tgId);

        if ($dto === null) {
            return $aiContext;
        }

        $dto->addUser($aiContext->getContext()[0]["content"]);

        return $dto;
    }
}

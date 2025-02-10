<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Resources;

use fholbrook\Openrouter\Exceptions\OpenRouterException;
use fholbrook\Openrouter\DTO\ChatRequest;
use fholbrook\Openrouter\DTO\ChatResponse;
use fholbrook\Openrouter\DTO\ResponseData;
use fholbrook\Openrouter\Serializer\OpenRouterSerializer;
use Psr\Http\Message\ResponseInterface;

final class ChatResource extends AbstractResource
{
    /**
     * @param ChatRequest $chatRequest
     * @return ChatResponse
     * @throws OpenRouterException
     *
     * Performs a completion/chat.
     */
    public function chat(ChatRequest $chatRequest): ChatResponse
    {
        if (!$chatRequest->model) {
            $chatRequest->model = $this->config->defaultModel;
        }

        try {
            $response = $this->config->getClient()->post('chat/completions', [
                'json' => OpenRouterSerializer::toArray($chatRequest)
            ]);
        } catch (\Throwable $e) {
            throw new OpenRouterException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }


        return new ChatResponse($chatRequest, $this->handleResponse($response));
    }


    private function handleResponse(ResponseInterface $response): ResponseData
    {
        $data = self::jsonDecode($response);

        if (isset($data['error'])) {
            throw $this->parseError($data['error']);
        }

        return ResponseData::fromArray(OpenRouterSerializer::arrayToCamelCase($data));
    }
}
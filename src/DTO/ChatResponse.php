<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

use fholbrook\Openrouter\Stamps\CreatedAtStamp;
use fholbrook\Openrouter\Stamps\ModelStamp;

final class ChatResponse
{

    public function __construct(
        private ChatRequest $chatRequest,
        private ResponseData $responseData,
    )
    {
    }

    public function getRequest(): ChatRequest
    {
        return $this->chatRequest;
    }

    public function getResponse(): ResponseData
    {
        return $this->responseData;
    }

    public function getChat(): ?Chat
    {
        if (!$this->chatRequest->chat) {
            return null;
        }

        $chat = $this->chatRequest->chat;
        /** @var Message $message */
        $message = $this->responseData->choices[0];

        $message->addStamp($this->responseData->usage);
        $message->addStamp(new CreatedAtStamp(\DateTimeImmutable::createFromFormat('U', (string)$this->responseData->created)));
        $message->addStamp(new ModelStamp($this->responseData->model));
        $chat->messages[] = $message;

        return $chat;
    }

}


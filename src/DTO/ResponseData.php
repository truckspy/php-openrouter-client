<?php

declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

/**
 * ResponseData is the general response DTO which consists of:
 * - id
 * - provider
 * - model
 * - object
 * - created
 * - choices (DTO object)
 * - usage (DTO object)
 *
 * Class ResponseData
 * @package fholbrook\Openrouter\DTO
 */
final class ResponseData
{

    public function __construct(
        /**
         * ID of the request which later can be used for cost request
         *
         * @var string
         */
        public string $id,

        /**
         * Name of the model e.g. mistralai/mistral-7b-instruct:free
         *
         * @var string
         */
        public string $model,

        /**
         * e.g. 'chat.completion' | 'chat.completion.chunk'
         *
         * @var string
         */
        public string $object,

        /**
         * Unix timestamp of created_at e.g. 1715621307
         *
         * @var int
         */
        public int $created,

        /**
         * Model provider e.g. HuggingFace
         *
         * @var string|null
         */
        public ?string $provider = null,

        /**
         * Depending on whether you set "stream" to "true"
         * and whether you passed in "messages" or a "prompt", you get a different output shape.
         *
         * @var NonChatChoice[]|Message[]|null
         */
        public ?array $choices = null,

        /**
         * Usage information of api request.
         *
         * @var Usage|null
         */
        public ?Usage $usage = null,

        /**
         * If using Perplexity Sonar, will return citations
         *
         * @var string[]|null
         */
        public ?array $citations = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $c = null;
        if (isset($data['choices'][0]['text'])) {
            $c = array_map(fn($choice) => new NonChatChoice($choice['text'], $choice['finishReason']), $data['choices']);
        } elseif (isset($data['choices'][0]['message'])) {
            $c = array_map(fn($choice) => Message::fromArray($choice), array_map(fn(array $choice) => $choice['message'], $data['choices']));
        }

        return new self(
            $data['id'],
            $data['model'],
            $data['object'],
            $data['created'],
            $data['provider'],
            $c,
            $data['usage'] ? Usage::fromArray($data['usage']) : null,
            isset($data['citations']) ? $data['citations'] : null
        );
    }
}
<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

final class ResponseFormat
{
    public function __construct(
        /**
         * The format of the output, e.g. json, text, srt, verbose_json ...
         *
         * @var string
         */
        public string $type,

        /**
         * The JSON schema for the output format.
         *
         * @var mixed
         */
        public mixed $jsonSchema = null
    ) {

    }

    public function toArray(): array
    {
        return array_filter(
            [
                'type' => $this->type,
                'jsonSchema' => (is_object($this->jsonSchema) ? $this->jsonSchema->toArray() : $this->jsonSchema),
            ]
        );
    }
}
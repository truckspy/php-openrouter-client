<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

use fholbrook\Openrouter\Contracts\ContentInterface;

final class TextContent implements ContentInterface
{
    public function __construct(
        /**
         * The text content.
         *
         * @var string
         */
        public string $text
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'text',
            'text' => $this->text,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['text']
        );
    }
}
<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

use fholbrook\Openrouter\Contracts\ContentInterface;
use fholbrook\Openrouter\Exceptions\OpenRouterException;

/**
 * OpenAI Spec Image content item. Supports both URL and base64 encoded image data.
 */
final class ImageContent implements ContentInterface
{
    public function __construct(
        /**
         * URL or base64 encoded image data
         *
         * @var string
         */
        public string $url,

        /**
         * Optional, defaults to 'image_url'
         *
         * @var string|null
         */
        public string $type = 'image_url'
    ) {
    }

    public static function fromFile(string $path): self
    {
        if (realpath($path) === false) {
            throw new OpenRouterException('File not found: `'.$path.'`');
        }

        $content = file_get_contents($path);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($path);

        return new self(
            sprintf('data:%s;base64,%s', $mimeType, base64_encode($content))
        );
    }
    public static function fromContent(?string $content): self
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($content);
        return new self(
            sprintf('data:%s;base64,%s', $mimeType, base64_encode($content))
        );
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'image_url' => ['url' => $this->url],
                'type' => $this->type,
            ]
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['image_url']['url'],
            $data['type'] ?? 'image_url'
        );
    }
}
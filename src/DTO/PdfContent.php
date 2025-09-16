<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

use fholbrook\Openrouter\Contracts\ContentInterface;
use fholbrook\Openrouter\Exceptions\OpenRouterException;

/**
 * OpenRouter PDF content item. Supports both URL and base64 encoded PDF data.
 */
final class PdfContent implements ContentInterface
{
    public function __construct(
        /**
         * URL or base64 encoded PDF data
         *
         * @var string
         */
        public string $fileData,

        /**
         * Optional filename for the PDF
         *
         * @var string|null
         */
        public ?string $filename = null,

        /**
         * Content type, defaults to 'file'
         *
         * @var string
         */
        public string $type = 'file'
    ) {
    }

    public static function fromFile(string $path, ?string $filename = null): self
    {
        if (realpath($path) === false) {
            throw new OpenRouterException('File not found: `'.$path.'`');
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new OpenRouterException('Unable to read file: `'.$path.'`');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($path);

        if ($mimeType !== 'application/pdf') {
            throw new OpenRouterException('File is not a PDF: `'.$path.'`');
        }

        return new self(
            sprintf('data:%s;base64,%s', $mimeType, base64_encode($content)),
            $filename ?? basename($path)
        );
    }

    public static function fromContent(string $content, ?string $filename = null): self
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($content);

        if ($mimeType !== 'application/pdf') {
            throw new OpenRouterException('Content is not a PDF');
        }

        return new self(
            sprintf('data:%s;base64,%s', $mimeType, base64_encode($content)),
            $filename ?? 'document.pdf'
        );
    }

    public static function fromUrl(string $url, ?string $filename = null): self
    {
        if (empty($filename)) {
            $filename = 'document.pdf';
        }
        return new self(
            $url,
            $filename
        );
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'file' => array_filter([
                    'file_data' => $this->fileData,
                    'filename' => $this->filename,
                ]),
                'type' => $this->type,
            ]
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['file']['file_data'],
            $data['file']['filename'] ?? null,
            $data['type'] ?? 'file'
        );
    }
}

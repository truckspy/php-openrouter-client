<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

use fholbrook\Openrouter\Contracts\StampInterface;

final class Usage implements StampInterface
{

    public function __construct(
        public ?int $promptTokens = null,
        public ?int $completionTokens = null,
        public ?int $totalTokens = null,
    )
    {
    }

    public function toArray(): array
    {
        $a['promptTokens'] = $this->promptTokens;
        $a['completionTokens'] = $this->completionTokens;
        $a['totalTokens'] = $this->totalTokens;

        return array_filter($a);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['promptTokens'],
            $data['completionTokens'],
            $data['totalTokens']
        );
    }
}
<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Stamps;

use fholbrook\Openrouter\Contracts\StampInterface;

class CreatedAtStamp implements StampInterface
{
    public function __construct(
        public \DateTimeImmutable $createdAt,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'createdAt' => $this->createdAt->format(\DATE_RFC3339),
        ];
    }

    public static function fromArray(array $data): StampInterface
    {
        return new self(
            \DateTimeImmutable::createFromFormat(DATE_RFC3339, $data['createdAt']),
        );
    }
}
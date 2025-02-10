<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Contracts;

interface StampInterface
{
    public function toArray(): array;

    public static function fromArray(array $data): self;
}
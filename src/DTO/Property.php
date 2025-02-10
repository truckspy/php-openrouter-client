<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

class Property
{
    public function __construct(
        public string $name,
        public array|string $type,
        public string $description,
        public bool $required = true,
        public array $enum = [],
    )
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'description' => $this->description,
            'enum' => $this->enum,
        ]);
    }
}
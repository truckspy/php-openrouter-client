<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

class JsonSchema
{

    public function __construct(
        public string $name,
        public Schema $schema,
        public bool $strict = true,
    )
    {
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'name' => $this->name,
                'strict' => $this->strict,
                'schema' => $this->schema->toArray()
            ]
        );
    }
}
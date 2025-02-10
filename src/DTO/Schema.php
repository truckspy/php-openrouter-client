<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

class Schema
{

    public function __construct(
        public string $type = 'object',
        /**
         * @var array|Property[]
         */
        public array $properties = [],
    )
    {
    }

    public function toArray(): array
    {
        $props = [];
        foreach ($this->properties as $property) {
            $props[$property->name] = $property->toArray();
        }

        return [
            'type' => $this->type,
            'properties' => $props,
            'required' => array_map(fn($property) => $property->name, array_filter($this->properties, fn($property) => $property->required)),
            'additionalProperties' => false
        ];

    }
}
<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

final class ToolCall
{
    public function __construct(

        /**
         * Name of the tool. (i.e. function)
         *
         * @var string|null
         */
        public ?string $type = null,

        /**
         * Function DTO object.
         *
         * @var FunctionData|null
         */
        public ?FunctionData $function = null,
        /**
         * ID of the tool call.
         *
         * @var string|null
         */
        public ?string $id = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'id' => $this->id,
                'type' => $this->type,
                'function' => $this->function ? $this->function->toArray() : null,
            ]
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['type'],
            $data['function'] ? FunctionData::fromArray($data['function']) : null,
            $data['id']
        );
    }
}
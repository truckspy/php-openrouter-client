<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

class Chat
{
    public function __construct(
        /**
         * Implementation specific chat Id.
         *
         * @var string|null
         */
        public ?string $id = null,

        /**
         * The messages in the chat.
         *
         * @var Message[]
         */
        public array $messages = []
    ) {}


    public function toArray(bool $includeMetadata = false): array
    {
        $a['messages'] = array_map(
            fn (Message $message) => $message->toArray($includeMetadata),
            $this->messages
        );

        if ($includeMetadata) {
            $a['id'] = $this->id;
        }

        return array_filter($a);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            array_map(
                fn (array $message) => Message::fromArray($message),
                $data['messages']
            )
        );
    }
}
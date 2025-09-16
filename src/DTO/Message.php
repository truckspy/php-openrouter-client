<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

use fholbrook\Openrouter\Contracts\StampInterface;

class Message
{
    public function __construct(
        /**
         * The content of the message.
         *
         * @var string|TextContent[]|ImageContent[]|PdfContent[]|array|null
         */
        public string|array|null $content = null,

        /**
         * The entity that produced the message.
         * Possible values are user, assistant, system, function, tool
         *
         * @var string|null
         */
        public ?string $role = null,

        /**
         * Calling tools e.g. function
         *
         * @var ToolCall[]|null
         */
        public ?array $toolCalls = null,

        /**
         * An optional name for the participant. Provides the model information to differentiate between participants of the same role.
         * e.g. name: "Moe"
         *
         * @var string|null
         */
        public ?string $name = null,

        /**
         * @var StampInterface[]
         */
        public array $stamps = []
    ) {

    }

    public function getStampByFQDN(string $fqdn): ?StampInterface
    {
        foreach ($this->stamps as $stamp) {
            if ($stamp::class === $fqdn) {
                return $stamp;
            }
        }
        return null;
    }

    public function addStamp(?StampInterface $stamp): void
    {
        if ($stamp instanceof StampInterface) {
            $this->stamps[] = $stamp;

        }
    }

    public function toArray(bool $includeStamps = false): array
    {
        $a = array_filter(
            [
                'content' => (is_array($this->content) ? array_map(fn($content) => (is_object($content) ? $content->toArray() : $content), $this->content) : $this->content),
                'role' => $this->role,
                'toolCalls' => $this->toolCalls ? array_map(fn($toolCall) => $toolCall->toArray(), $this->toolCalls) : null,
                'name' => $this->name
            ]
        );

        if ($includeStamps) {
            $a['stamps'] = $this->stamps ? array_map(fn(StampInterface $stamp) => array_merge($stamp->toArray(), ['fqdn' => get_class($stamp)]), $this->stamps) : null;
        }

        return $a;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['content'],
            $data['role'],
            !empty($data['toolCalls']) ? array_map(fn($toolCall) => ToolCall::fromArray($toolCall), $data['toolCalls']) : null,
            isset($data['name']) ? $data['name'] : null,
            !empty($data['stamps']) ? array_map(fn(array $stamp) => self::stampFromArray($stamp), $data['stamps']) : []
        );
    }

    private static function stampFromArray(array $data): StampInterface
    {
        $fqdn = $data['fqdn'];
        return $fqdn::fromArray($data);
    }
}

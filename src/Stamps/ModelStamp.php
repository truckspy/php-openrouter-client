<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Stamps;

use fholbrook\Openrouter\Contracts\StampInterface;

class ModelStamp implements StampInterface
{
    public function __construct(
        public string $model,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
        ];
    }

    public static function fromArray(array $data): StampInterface
    {
        return new self(
            $data['model'],
        );
    }


}
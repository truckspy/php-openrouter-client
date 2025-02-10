<?php
declare(strict_types=1);

namespace fholbrook\Openrouter;

use fholbrook\Openrouter\Resources\ChatResource;
use fholbrook\Openrouter\Resources\ModelsResource;

class OpenRouter
{
    public ChatResource $chat;

    public ModelsResource $models;

    public function __construct(
        private readonly OpenRouterConfig $config
    )
    {
        $this->chat = new ChatResource($this->config);
        $this->models = new ModelsResource($this->config);
    }
}
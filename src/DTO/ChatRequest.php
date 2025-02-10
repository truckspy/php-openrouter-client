<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

final class ChatRequest
{
    public function __construct(
        /**
         * Chat Object. When provided prompt is ignored.
         *
         * @var Chat|null
         */
        public ?Chat $chat = null,

        /**
         * Prompt string data. If "chat" is specified, this is ignored.
         *
         * @var string|null
         */
        public ?string $prompt = null,

        /**
         * Model name. If "model" is unspecified, uses the user's default.
         * For more info: https://openrouter.ai/docs#models
         *
         * @var string|null
         */
        public ?string $model = null,

        /**
         * The format of the output, e.g. json, text, srt, verbose_json ...
         *
         * @var ResponseFormat|null
         */
        public ?ResponseFormat $responseFormat = null,

        /**
         * Stop generation immediately if the model encounters any token specified in the stop array|string.
         *
         * @var array|string|null
         */
        public array|string|null $stop = null,

        /**
         * Enable streaming.
         *
         * @var bool|null
         */
        public ?bool $stream = null,

        /**
         * See LLM Parameters (https://openrouter.ai/docs#parameters) for following:
         */
        public ?int $maxTokens = 1024, // Range: [1, context_length) The maximum number of tokens that can be generated in the completion. Default 1024.
        public ?float $temperature = null, // Range: [0, 2] Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic.
        public ?float $topP = null, // Range: (0, 1] An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass.
        public ?float $topK = null, // Range: [1, Infinity) Not available for OpenAI models
        public ?float $frequencyPenalty = null, // Range: [-2, 2] Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.
        public ?float $presencePenalty = null, // Range: [-2, 2] Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics.
        public ?float $repetitionPenalty = null, // Range: (0, 2]
        public ?int $seed = null, // OpenAI only. This feature is in Beta. If specified, our system will make a best effort to sample deterministically, such that repeated requests with the same seed and parameters should return the same result.

        // Function-calling
        /**
         * Only natively supported by OpenAI models. For others, we submit a YAML-formatted string with these tools at the end of the prompt.
         *
         * @var string|array|null
         */
        public string|array|null $toolChoice = null, // none|auto or ToolCallData as {"type": "function", "function": {"name": "my_function"}}

        /**
         * Tool calls (also known as function calling) allow you to give an LLM access to external tools.
         *
         * @var ToolCall[]|null
         */
        public ?array $tools = null,

        // Additional optional parameters
        /**
         * Modify the likelihood of specified tokens appearing in the completion. e.g. {"50256": -100}
         */
        public ?array $logitBias = null,

        // OpenRouter-only parameters
        /**
         * See "Prompt Transforms" section: https://openrouter.ai/docs#transforms
         *
         * @var array|null
         */
        public ?array $transforms = null,

        /**
         * The models array, which lets you automatically try other models if the primary model's providers are down,
         * rate-limited, or refuse to reply due to content moderation required by all providers.
         *
         * @var array|null
         */
        public ?array $models = null,

        /**
         * @var string|null
         */
        public ?string $route = null,

        /**
         * See "Provider Routing" section: https://openrouter.ai/docs#provider-routing
         *
         * @var ProviderPreferences|null
         */
        public ?ProviderPreferences $provider = null,

        /**
         * Enable think tokens.
         * Default: false
         *
         * @var bool|null
         */
        public ?bool $includeReasoning = false,
    )
    {
    }

    public function toArray(bool $includeMeta = false): array
    {
        if ($this->chat && count($this->chat->messages)>0) {
            $this->prompt = null;
        }

        return array_filter(
            [
                'messages' => ($this->chat && count($this->chat->messages)>0) ? $this->chat->toArray($includeMeta)['messages'] : null,
                'prompt' => $this->prompt,
                'model' => $this->model,
                'responseFormat' => $this->responseFormat ? $this->responseFormat->toArray() : null,
                'stop' => $this->stop,
                'stream' => $this->stream,
                'maxTokens' => $this->maxTokens,
                'temperature' => $this->temperature,
                'topP' => $this->topP,
                'topK' => $this->topK,
                'frequencyPenalty' => $this->frequencyPenalty,
                'presencePenalty' => $this->presencePenalty,
                'repetitionPenalty' => $this->repetitionPenalty,
                'seed' => $this->seed,
                'toolChoice' => $this->toolChoice,
                'tools' => $this->tools ? array_map(fn($tool) => $tool->toArray(), $this->tools) : null,
                'logitBias' => $this->logitBias,
                'transforms' => $this->transforms,
                'models' => $this->models,
                'route' => $this->route,
                'provider' => $this->provider ? $this->provider->toArray() : null,
                'includeReasoning' => $this->includeReasoning,
            ]
        );
    }
}
<?php

declare(strict_types=1);

namespace fholbrook\Openrouter\DTO;

/**
 * NonChatChoiceData is the DTO choice type for non-chat responses
 *
 * Class NonChatChoiceData
 * @package fholbrook\Openrouter\DTO
 */
final class NonChatChoice
{

    public function __construct(
        /**
         * The text of the choice
         */
        public string $text,
        public ?string $finishReason = null
    ) {
    }



}
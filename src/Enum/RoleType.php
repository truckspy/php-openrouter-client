<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Enum;

enum RoleType: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case SYSTEM = 'system';
    case FUNCTION = 'function';
    case TOOL = 'tool';
}
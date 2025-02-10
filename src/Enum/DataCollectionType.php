<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Enum;

enum DataCollectionType: string
{
    case ALLOW = 'allow';
    case DENY = 'deny';
}
# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP OpenRouter Client library that provides an easy-to-use interface for integrating OpenRouter (a unified interface for LLMs) into PHP applications. The library follows modern PHP practices with PHP 8.2+ requirements and uses Guzzle HTTP for API communication.

## Development Commands

### Testing
```bash
# Run all tests
composer test
# OR
phpunit

# Run specific test
phpunit tests/DTO/ChatTest.php
```

### Static Analysis
```bash
# Run PHPStan (included in dev dependencies)
vendor/bin/phpstan analyze
```

### Dependencies
```bash
# Install dependencies
composer install

# Update dependencies
composer update
```

## Architecture Overview

### Core Components

**Main Client Classes:**
- `OpenRouter` (src/OpenRouter.php): Main client class with resource properties (`chat`, `models`)
- `OpenRouterConfig` (src/OpenRouterConfig.php): Configuration class handling API keys, timeouts, retry logic with Guzzle

**Resource Pattern:**
- `Resources/AbstractResource.php`: Base class for API resources
- `Resources/ChatResource.php`: Handles chat completions
- `Resources/ModelsResource.php`: Handles model information

**DTO Architecture:**
The library heavily uses DTOs (Data Transfer Objects) for structured data:
- `ChatRequest.php`: Main request object supporting both prompt strings and Chat objects
- `Chat.php`: Contains array of Message objects for conversation continuity
- `Message.php`: Individual messages with role, content, stamps metadata
- `ChatResponse.php`: API response wrapper

**Content System:**
- `Contracts/ContentInterface.php`: Interface for message content
- `TextContent.php` & `ImageContent.php`: Different content types
- `Contracts/StampInterface.php`: Metadata stamps on messages

**Configuration DTOs:**
- `ResponseFormat.php`, `Schema.php`, `Property.php`: For structured output
- `ToolCall.php`, `FunctionData.php`: For function calling
- `ProviderPreferences.php`: OpenRouter-specific settings

### Key Design Patterns

1. **Resource Pattern**: API endpoints are organized as resource classes accessed via the main client
2. **DTO Pattern**: Heavy use of DTOs for type safety and serialization/deserialization
3. **Factory Pattern**: OpenRouterConfig creates configured Guzzle clients with retry middleware
4. **Conversation Continuity**: Chat objects maintain message history and can be serialized/deserialized

### Testing Structure

Tests are organized by DTO classes in `tests/DTO/` directory. Each major DTO class has comprehensive unit tests covering serialization, validation, and behavior.

### Dependencies

**Runtime:**
- `guzzlehttp/guzzle ^7.9`: HTTP client
- `caseyamcl/guzzle_retry_middleware ^2.12`: Retry logic for API calls

**Development:**
- `phpunit/phpunit ^11.5`: Testing framework
- `phpstan/phpstan ^2.1`: Static analysis
- `mockery/mockery ^1.6`: Mocking for tests

### Configuration

The library supports environment variables for configuration:
- `OPENROUTER_API_KEY`: API key for OpenRouter
- `OPENROUTER_API_ENDPOINT`: Custom API endpoint (defaults to https://openrouter.ai/api/v1/)

These can be set in phpunit.xml for testing or passed directly to OpenRouterConfig constructor.
<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Resources;

use fholbrook\Openrouter\Exceptions\OpenRouterException;
use fholbrook\Openrouter\OpenRouterConfig;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractResource
{
    public function __construct(
        protected readonly OpenRouterConfig $config,
    )
    {
    }

    /**
     * Decodes response to json.
     *
     * @param ResponseInterface|null $response
     *
     * @return mixed|null
     */
    protected function jsonDecode(?ResponseInterface $response = null): mixed
    {
        // Get the response body or return null.
        return ($response ? json_decode((string) $response->getBody(), true) : null);
    }

    protected function parseError(array $data): OpenRouterException
    {
        if (isset($data['metadata']['raw'])) {
            return new OpenRouterException($data['metadata']['raw'], $data['code']);
        }

        return new OpenRouterException($data['message'], $data['code']);
    }
}
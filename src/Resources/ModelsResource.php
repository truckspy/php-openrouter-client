<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Resources;

use fholbrook\Openrouter\Exceptions\OpenRouterException;
use GuzzleHttp\Exception\ClientException;

final class ModelsResource extends AbstractResource
{
    /**
     * @return array
     * @throws OpenRouterException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * Lists available models
     */
    public function list(): array
    {
        try {
            $response = $this->config->getClient()->get('models');
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            throw new OpenRouterException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param string $model
     * @return array
     * @throws OpenRouterException
     *
     * Lists endpoints for available models.
     */
    public function endpoints(string $model): array
    {
        try {
            $response = $this->config->getClient()->get("models/{$model}/endpoints");
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            throw new OpenRouterException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
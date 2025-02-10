<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Serializer;

use fholbrook\Openrouter\Exceptions\OpenRouterException;

class OpenRouterSerializer
{
    public static function toArray(object $data): array
    {
        if (!method_exists($data, 'toArray')) {
            throw new OpenRouterException('The data object must have a `toArray` method.');
        }

        return self::arrayToSnakeCase($data->toArray());
    }

    public static function fromArray(array $data, string $className): object
    {
        if (!class_exists($className) || !method_exists($className, 'fromArray')) {
            throw new OpenRouterException('The class name provided does not exist or it does not implement fromArray.');
        }

        $data = self::arrayToCamelCase($data);

        return $className::fromArray($data);
    }

    public static function serialize(object $data): string
    {
        return json_encode(self::toArray($data));
    }

    public static function deserialize(string $json, string $className): object
    {
        return self::fromArray(json_decode($json, true), $className);
    }

    public static function arrayToCamelCase(array $data): array
    {
        $camelCased = [];
        foreach ($data as $key => $value) {

            if (in_array($key, ['json_schema', 'parameters'])) {
                $camelCased[self::toCamelCase($key)] = $value;
                continue;
            }

            if (is_string($key)) {
                $key = self::toCamelCase($key);
            }

            if (is_array($value)) {
                $value = self::arrayToCamelCase($value);
            }

            $camelCased[$key] = $value;
        }

        return $camelCased;
    }

    public static function arrayToSnakeCase(array $data): array
    {
        $snakeCased = [];
        foreach ($data as $key => $value) {
            if (in_array($key, ['jsonSchema', 'parameters'])) {
                $snakeCased[self::toSnakeCase($key)] = $value;
                continue;
            }


            if (is_string($key)) {
                $key = self::toSnakeCase($key);
            }

            if (is_array($value)) {
                $value = self::arrayToSnakeCase($value);
            }

            $snakeCased[$key] = $value;
        }

        return $snakeCased;
    }

    private static function toSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    private static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }
}
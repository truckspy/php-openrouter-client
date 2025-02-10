<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Tests\DTO;

use fholbrook\Openrouter\DTO\JsonSchema;
use fholbrook\Openrouter\DTO\Property;
use fholbrook\Openrouter\DTO\Schema;
use PHPUnit\Framework\TestCase;

class JsonSchemaTest extends TestCase
{
    public function testToArray(): void
    {
        $schema = new Schema(
            type: 'object',
            properties: [
                new Property('test', 'string', 'Test property')
            ]
        );

        $jsonSchema = new JsonSchema(
            name: 'test_schema',
            schema: $schema,
            strict: true
        );

        $expected = [
            'name' => 'test_schema',
            'strict' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'test' => ['type' => 'string', 'description' => 'Test property']
                ],
                'required' => ['test'],
                'additionalProperties' => false
            ]
        ];

        $this->assertEquals($expected, $jsonSchema->toArray());
    }

    public function testToArrayWithDefaultStrict(): void
    {
        $schema = new Schema(
            type: 'object',
            properties: [
                new Property('test', 'string', 'Test property')
            ]
        );

        $jsonSchema = new JsonSchema(
            name: 'test_schema',
            schema: $schema
        );

        $expected = [
            'name' => 'test_schema',
            'strict' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'test' => ['type' => 'string', 'description' => 'Test property']
                ],
                'required' => ['test'],
                'additionalProperties' => false
            ]
        ];

        $this->assertEquals($expected, $jsonSchema->toArray());
    }
}

<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Tests\DTO;

use fholbrook\Openrouter\DTO\ResponseFormat;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ResponseFormatTest extends TestCase
{
    public function testToArrayWithTypeOnly(): void
    {
        $responseFormat = new ResponseFormat('json');
        
        $expected = ['type' => 'json'];
        
        $this->assertEquals($expected, $responseFormat->toArray());
    }

    public function testToArrayWithJsonSchemaAsArray(): void 
    {
        $jsonSchema = ['foo' => 'bar'];
        $responseFormat = new ResponseFormat('json', $jsonSchema);
        
        $expected = [
            'type' => 'json',
            'jsonSchema' => ['foo' => 'bar']
        ];
        
        $this->assertEquals($expected, $responseFormat->toArray());
    }

    public function testToArrayWithJsonSchemaAsObject(): void
    {
        $jsonSchema = new class() {
            public function toArray(): array 
            {
                return ['foo' => 'bar'];
            }
        };
        
        $responseFormat = new ResponseFormat('json', $jsonSchema);
        
        $expected = [
            'type' => 'json',
            'jsonSchema' => ['foo' => 'bar']
        ];
        
        $this->assertEquals($expected, $responseFormat->toArray());
    }

    public function testToArrayWithNullJsonSchema(): void
    {
        $responseFormat = new ResponseFormat('json', null);
        
        $expected = ['type' => 'json'];
        
        $this->assertEquals($expected, $responseFormat->toArray());
    }
}

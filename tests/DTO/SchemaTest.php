<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Tests\DTO;

use fholbrook\Openrouter\DTO\Schema;
use fholbrook\Openrouter\DTO\Property;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    public function testConstructorDefaultValues()
    {
        $schema = new Schema('object', [new Property('zipcode', 'string', 'The zipcode to fetch weather for')]);
        $this->assertEquals('object', $schema->type);
        $this->assertEquals([new Property('zipcode', 'string', 'The zipcode to fetch weather for')], $schema->properties);
    }

    public function testToArrayWithNoProperties()
    {
        $schema = new Schema('object', [new Property('zipcode', 'string', 'The zipcode to fetch weather for')]);
        $expected = [
            'type' => 'object',
            'properties' => [
                'zipcode' => ['type' => 'string', 'description' => 'The zipcode to fetch weather for']
            ],
            'required' => ['zipcode'],
            'additionalProperties' => false
        ];
        $this->assertEquals($expected, $schema->toArray());
    }

    public function testToArrayWithProperties()
    {
        $property1 = new Property('name', 'string', 'desc');
        $property2 = new Property('age', 'integer', 'desc');
        
        $schema = new Schema('object', [$property1, $property2]);
        
        $expected = [
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string', 'description' => 'desc'],
                'age' => ['type' => 'integer', 'description' => 'desc']
            ],
            'required' => ['name', 'age'],
            'additionalProperties' => false
        ];
        
        $this->assertEquals($expected, $schema->toArray());
    }

    public function testToArrayWithCustomType()
    {
        $schema = new Schema('array');
        $expected = [
            'type' => 'array',
            'properties' => [],
            'required' => [],
            'additionalProperties' => false
        ];
        $this->assertEquals($expected, $schema->toArray());
    }
}

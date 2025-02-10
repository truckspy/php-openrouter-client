<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Tests\DTO;

use fholbrook\Openrouter\DTO\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testConstructor(): void
    {
        $property = new Property(
            name: 'test',
            type: 'string',
            description: 'Test description',
            required: true,
            enum: ['value1', 'value2']
        );

        $this->assertEquals('test', $property->name);
        $this->assertEquals('string', $property->type);
        $this->assertEquals('Test description', $property->description);
        $this->assertTrue($property->required);
        $this->assertEquals(['value1', 'value2'], $property->enum);
    }

    public function testToArray(): void
    {
        $property = new Property(
            name: 'test',
            type: 'string',
            description: 'Test description',
            required: true,
            enum: ['value1', 'value2']
        );

        $expected = [
            'type' => 'string',
            'description' => 'Test description',
            'enum' => ['value1', 'value2']
        ];

        $this->assertEquals($expected, $property->toArray());
    }

    public function testToArrayWithEmptyEnum(): void
    {
        $property = new Property(
            name: 'test',
            type: 'string',
            description: 'Test description'
        );

        $expected = [
            'type' => 'string',
            'description' => 'Test description'
        ];

        $this->assertEquals($expected, $property->toArray());
    }

    public function testWithArrayType(): void
    {
        $property = new Property(
            name: 'test',
            type: ['string', 'integer'],
            description: 'Test description'
        );

        $this->assertEquals(['string', 'integer'], $property->type);
    }
}

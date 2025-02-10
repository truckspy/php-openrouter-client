<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Tests\DTO;

use fholbrook\Openrouter\DTO\Usage;
use PHPUnit\Framework\TestCase;

class UsageTest extends TestCase
{
    public function testCanCreateUsageInstance(): void 
    {
        $usage = new Usage(100, 50, 150);
        
        $this->assertInstanceOf(Usage::class, $usage);
        $this->assertEquals(100, $usage->promptTokens);
        $this->assertEquals(50, $usage->completionTokens);
        $this->assertEquals(150, $usage->totalTokens);
    }

    public function testCanCreateWithNullValues(): void
    {
        $usage = new Usage();
        
        $this->assertNull($usage->promptTokens);
        $this->assertNull($usage->completionTokens);
        $this->assertNull($usage->totalTokens);
    }

    public function testToArrayReturnsCorrectArray(): void
    {
        $usage = new Usage(100, 50, 150);
        
        $expected = [
            'promptTokens' => 100,
            'completionTokens' => 50,
            'totalTokens' => 150
        ];
        
        $this->assertEquals($expected, $usage->toArray());
    }

    public function testToArrayFiltersNullValues(): void
    {
        $usage = new Usage(100, null, 150);
        
        $expected = [
            'promptTokens' => 100,
            'totalTokens' => 150
        ];
        
        $this->assertEquals($expected, $usage->toArray());
    }

    public function testFromArrayCreatesCorrectInstance(): void
    {
        $data = [
            'promptTokens' => 100,
            'completionTokens' => 50,
            'totalTokens' => 150
        ];

        $usage = Usage::fromArray($data);
        
        $this->assertInstanceOf(Usage::class, $usage);
        $this->assertEquals(100, $usage->promptTokens);
        $this->assertEquals(50, $usage->completionTokens);
        $this->assertEquals(150, $usage->totalTokens);
    }
}

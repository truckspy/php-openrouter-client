<?php

namespace fholbrook\Openrouter\Tests\DTO;

use fholbrook\Openrouter\DTO\TextContent;
use PHPUnit\Framework\TestCase;

class TextContentTest extends TestCase
{
    public function testToArray(): void
    {
        $text = 'Hello world';
        $content = new TextContent($text);

        $expected = [
            'type' => 'text',
            'text' => $text
        ];

        $this->assertEquals($expected, $content->toArray());
    }

    public function testFromArray(): void
    {
        $data = [
            'text' => 'Hello world'
        ];

        $content = TextContent::fromArray($data);

        $this->assertInstanceOf(TextContent::class, $content);
        $this->assertEquals($data['text'], $content->text);
    }

    public function testConstructorSetsText(): void
    {
        $text = 'Hello world';
        $content = new TextContent($text);

        $this->assertEquals($text, $content->text);
    }
}

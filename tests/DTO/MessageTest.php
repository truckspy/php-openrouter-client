<?php

namespace fholbrook\Openrouter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use fholbrook\Openrouter\DTO\Message;
use fholbrook\Openrouter\DTO\ToolCall;
use fholbrook\Openrouter\Contracts\StampInterface;

class MessageTest extends TestCase
{
    public function testConstruction()
    {
        $message = new Message(
            'test content',
            'user',
            null,
            'John',
            []
        );

        $this->assertEquals('test content', $message->content);
        $this->assertEquals('user', $message->role);
        $this->assertNull($message->toolCalls);
        $this->assertEquals('John', $message->name);
        $this->assertEmpty($message->stamps);
    }

    public function testGetStampByFQDN()
    {
        $stamp = $this->createMock(StampInterface::class);
        $message = new Message();
        $message->addStamp($stamp);

        $result = $message->getStampByFQDN(get_class($stamp));
        $this->assertSame($stamp, $result);

        $nonExistentResult = $message->getStampByFQDN('NonExistentClass');
        $this->assertNull($nonExistentResult);
    }

    public function testAddStamp()
    {
        $message = new Message();
        $stamp = $this->createMock(StampInterface::class);
        
        $message->addStamp($stamp);
        $this->assertCount(1, $message->stamps);
        $this->assertSame($stamp, $message->stamps[0]);

        // Test adding null stamp
        $message->addStamp(null);
        $this->assertCount(1, $message->stamps);
    }


    public function testFromArray()
    {
        $data = [
            'content' => 'test content',
            'role' => 'user',
            'name' => 'John'
        ];

        $message = Message::fromArray($data);

        $this->assertEquals('test content', $message->content);
        $this->assertEquals('user', $message->role);
        $this->assertEquals('John', $message->name);
    }
}

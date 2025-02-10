<?php
declare(strict_types=1);

namespace fholbrook\Openrouter\Tests\DTO;

use DateTimeImmutable;
use fholbrook\Openrouter\DTO\Chat;
use fholbrook\Openrouter\DTO\ChatRequest;
use fholbrook\Openrouter\DTO\ChatResponse;
use fholbrook\Openrouter\DTO\Message;
use fholbrook\Openrouter\DTO\ResponseData;
use fholbrook\Openrouter\DTO\Usage;
use fholbrook\Openrouter\Stamps\CreatedAtStamp;
use fholbrook\Openrouter\Stamps\ModelStamp;
use PHPUnit\Framework\TestCase;

class ChatResponseTest extends TestCase
{
    public function testGetRequest(): void
    {
        $chatRequest = new ChatRequest();
        $responseData = new ResponseData('test-id', 'model', 'chat.completion', 1234567);
        
        $chatResponse = new ChatResponse($chatRequest, $responseData);
        
        $this->assertSame($chatRequest, $chatResponse->getRequest());
    }

    public function testGetResponse(): void
    {
        $chatRequest = new ChatRequest();
        $responseData = new ResponseData('test-id', 'model', 'chat.completion', 1234567);
        
        $chatResponse = new ChatResponse($chatRequest, $responseData);
        
        $this->assertSame($responseData, $chatResponse->getResponse());
    }

    public function testGetChatReturnsNullWhenNoChatInRequest(): void
    {
        $chatRequest = new ChatRequest();
        $responseData = new ResponseData('test-id', 'model', 'chat.completion', 1234567);
        
        $chatResponse = new ChatResponse($chatRequest, $responseData);
        
        $this->assertNull($chatResponse->getChat());
    }

    public function testGetChatReturnsUpdatedChat(): void
    {
        $chat = new Chat();
        $chatRequest = new ChatRequest();
        $chatRequest->chat = $chat;
        
        $message = new Message();
        $responseData = new ResponseData('test-id', 'model', 'chat.completion', 1234567);
        $responseData->choices = [$message];
        $responseData->usage = new Usage();
        $responseData->created = 1234567890;
        $responseData->model = 'test-model';
        
        $chatResponse = new ChatResponse($chatRequest, $responseData);
        
        $resultChat = $chatResponse->getChat();
        
        $this->assertSame($chat, $resultChat);
        $this->assertCount(1, $resultChat->messages);
        $this->assertSame($message, $resultChat->messages[0]);
        
        $stamps = $message->stamps;
        $this->assertCount(3, $stamps);
        $this->assertInstanceOf(Usage::class, $stamps[0]);
        $this->assertInstanceOf(CreatedAtStamp::class, $stamps[1]);
        $this->assertInstanceOf(ModelStamp::class, $stamps[2]);
        
        /** @var CreatedAtStamp $createdAtStamp */
        $createdAtStamp = $stamps[1];
        $this->assertEquals(
            DateTimeImmutable::createFromFormat('U', '1234567890'),
            $createdAtStamp->createdAt
        );
        
        /** @var ModelStamp $modelStamp */
        $modelStamp = $stamps[2];
        $this->assertEquals('test-model', $modelStamp->model);
    }
}

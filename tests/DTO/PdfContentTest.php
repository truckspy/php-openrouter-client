<?php

namespace fholbrook\Openrouter\Tests\Unit\DTO;

use fholbrook\Openrouter\DTO\PdfContent;
use fholbrook\Openrouter\Exceptions\OpenRouterException;
use PHPUnit\Framework\TestCase;

class PdfContentTest extends TestCase
{
    public function testConstructor()
    {
        $content = new PdfContent('http://example.com/document.pdf', 'test.pdf');
        $this->assertEquals('http://example.com/document.pdf', $content->fileData);
        $this->assertEquals('test.pdf', $content->filename);
        $this->assertEquals('file', $content->type);
    }

    public function testConstructorWithDefaults()
    {
        $content = new PdfContent('http://example.com/document.pdf');
        $this->assertEquals('http://example.com/document.pdf', $content->fileData);
        $this->assertNull($content->filename);
        $this->assertEquals('file', $content->type);
    }

    public function testFromFile()
    {
        // Create temp test PDF file with PDF header
        $tmpFile = tempnam(sys_get_temp_dir(), 'test') . '.pdf';
        file_put_contents($tmpFile, '%PDF-1.4' . "\n" . 'test content');

        $content = PdfContent::fromFile($tmpFile);

        $this->assertStringStartsWith('data:application/pdf;base64,', $content->fileData);
        $this->assertEquals(basename($tmpFile), $content->filename);

        unlink($tmpFile);
    }

    public function testFromFileWithCustomFilename()
    {
        // Create temp test PDF file with PDF header
        $tmpFile = tempnam(sys_get_temp_dir(), 'test') . '.pdf';
        file_put_contents($tmpFile, '%PDF-1.4' . "\n" . 'test content');

        $content = PdfContent::fromFile($tmpFile, 'custom.pdf');

        $this->assertStringStartsWith('data:application/pdf;base64,', $content->fileData);
        $this->assertEquals('custom.pdf', $content->filename);

        unlink($tmpFile);
    }

    public function testFromFileThrowsExceptionForNonexistentFile()
    {
        $this->expectException(OpenRouterException::class);
        $this->expectExceptionMessage('File not found: `nonexistent.pdf`');
        PdfContent::fromFile('nonexistent.pdf');
    }

    public function testFromFileThrowsExceptionForNonPdfFile()
    {
        // Create temp test file that's not a PDF
        $tmpFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($tmpFile, 'not a pdf');

        $this->expectException(OpenRouterException::class);
        $this->expectExceptionMessage('File is not a PDF: `' . $tmpFile . '`');

        PdfContent::fromFile($tmpFile);

        unlink($tmpFile);
    }

    public function testFromContent()
    {
        $pdfContent = '%PDF-1.4' . "\n" . 'test content';
        $content = PdfContent::fromContent($pdfContent, 'test.pdf');

        $this->assertStringStartsWith('data:application/pdf;base64,', $content->fileData);
        $this->assertEquals('test.pdf', $content->filename);
    }

    public function testFromContentWithDefaultFilename()
    {
        $pdfContent = '%PDF-1.4' . "\n" . 'test content';
        $content = PdfContent::fromContent($pdfContent);

        $this->assertStringStartsWith('data:application/pdf;base64,', $content->fileData);
        $this->assertEquals('document.pdf', $content->filename);
    }

    public function testFromContentThrowsExceptionForNonPdfContent()
    {
        $this->expectException(OpenRouterException::class);
        $this->expectExceptionMessage('Content is not a PDF');

        PdfContent::fromContent('not a pdf');
    }

    public function testFromUrl()
    {
        $content = PdfContent::fromUrl('http://example.com/document.pdf');

        $this->assertEquals('http://example.com/document.pdf', $content->fileData);
        $this->assertEquals('document.pdf', $content->filename);
    }

    public function testFromUrlWithCustomFilename()
    {
        $content = PdfContent::fromUrl('http://example.com/document.pdf', 'custom.pdf');

        $this->assertEquals('http://example.com/document.pdf', $content->fileData);
        $this->assertEquals('custom.pdf', $content->filename);
    }

    public function testFromUrlWithNoPath()
    {
        $content = PdfContent::fromUrl('http://example.com/');

        $this->assertEquals('http://example.com/', $content->fileData);
        $this->assertEquals('document.pdf', $content->filename);
    }

    public function testToArray()
    {
        $content = new PdfContent('http://example.com/document.pdf', 'test.pdf');
        $array = $content->toArray();

        $this->assertEquals([
            'file' => [
                'file_data' => 'http://example.com/document.pdf',
                'filename' => 'test.pdf'
            ],
            'type' => 'file'
        ], $array);
    }

    public function testToArrayWithoutFilename()
    {
        $content = new PdfContent('http://example.com/document.pdf');
        $array = $content->toArray();

        $this->assertEquals([
            'file' => [
                'file_data' => 'http://example.com/document.pdf'
            ],
            'type' => 'file'
        ], $array);
    }

    public function testFromArray()
    {
        $array = [
            'file' => [
                'file_data' => 'http://example.com/document.pdf',
                'filename' => 'test.pdf'
            ],
            'type' => 'custom_type'
        ];

        $content = PdfContent::fromArray($array);

        $this->assertEquals('http://example.com/document.pdf', $content->fileData);
        $this->assertEquals('test.pdf', $content->filename);
        $this->assertEquals('custom_type', $content->type);
    }

    public function testFromArrayWithDefaults()
    {
        $array = [
            'file' => [
                'file_data' => 'http://example.com/document.pdf'
            ]
        ];

        $content = PdfContent::fromArray($array);

        $this->assertEquals('http://example.com/document.pdf', $content->fileData);
        $this->assertNull($content->filename);
        $this->assertEquals('file', $content->type);
    }
}

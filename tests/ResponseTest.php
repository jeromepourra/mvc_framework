<?php
use core\response\Response;
use core\response\enum\EResponseCode;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetContent()
    {
        $response = new Response('Hello World');
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testGetStatusCode()
    {
        $response = new Response('Hello World', EResponseCode::OK);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddHeader()
    {
        $response = new Response('Hello World');
        $response->addHeader('Content-Type', 'text/plain');
        $this->assertEquals(['Content-Type' => 'text/plain'], $response->getHeaders());
    }

    public function testRemoveHeader()
    {
        $response = new Response('Hello World');
        $response->addHeader('Content-Type', 'text/plain');
        $response->removeHeader('Content-Type');
        $this->assertEquals([], $response->getHeaders());
    }

    public function testSetHeaders()
    {
        $response = new Response('Hello World');
        $response->setHeaders(['Content-Type' => 'text/plain', 'X-Header' => 'Value']);
        $this->assertEquals(['Content-Type' => 'text/plain', 'X-Header' => 'Value'], $response->getHeaders());
    }

    public function testGetHeader()
    {
        $response = new Response('Hello World');
        $response->addHeader('Content-Type', 'text/plain');
        $this->assertEquals('text/plain', $response->getHeader('Content-Type'));
        $this->assertNull($response->getHeader('X-Header'));
    }

    public function testSend()
    {
        $response = new Response('Hello World', EResponseCode::OK);
        ob_start();
        $response->send();
        $output = ob_get_clean();
        $this->assertEquals('Hello World', $output);
        $this->assertEquals(200, http_response_code());
    }
}
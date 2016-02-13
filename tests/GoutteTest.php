<?php

use Goutte;
use Goutte\Client;

class GoutteTest extends TestCase
{
	private $client;
	private $endpoint;

	public function setUp(){
		$this->client   = new Client();
		$this->endpoint = 'http://localhost:8080';
	}

	public function testGet(){
		// GET
		$this->client->setHeader('Accept', 'text/html');
		$crawler  = $this->client->request('GET', sprintf('%s/tweets', $this->endpoint));
		$response = $this->client->getResponse();
		$this->assertEquals(200, $response->getStatus());
		$this->assertEquals('text/html;charset=UTF-8', $response->getHeader('Content-Type'));
	}

	public function testGetJson(){
		// GET json
		$this->client->setHeader('Accept', 'application/json');
		$crawler  = $this->client->request('GET', sprintf('%s/tweets', $this->endpoint));
		$response = $this->client->getResponse();
		var_dump($response);
		$this->assertEquals(200, $response->getStatus());
		$this->assertEquals('application/json', $response->getHeader('Content-Type'));
	}

	public function testPost(){
		// POST
		// See: https://github.com/symfony/BrowserKit/blob/master/Client.php#L242
		$this->client->setHeader('Accept', 'text/html');
		$this->client->setHeader('Content-type', 'application/json');
		$headers = $request = [];
		$content = '{"nom":"Toto", "message":"ceci est un tweet", "date":"2016-01-01"}';
		$this->client->request('POST', sprintf('%s/tweet', $this->endpoint), $request, [], $headers, $content);
		$response = $this->client->getResponse();
		$this->assertEquals(200, $response->getStatus());

		// Examples of assertions:
		// $this->assertEquals(200, $response->getStatus());
		// $this->assertEquals('text/html', $response->getHeader('Content-Type'));
		//
		// $data = json_decode($response->getContent(), true);
		// $this->assertArrayHasKey('message', $data);
	}
}

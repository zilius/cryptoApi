<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Tests\TestCase;

class AssetControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected string $url;
    protected Client $client;
    protected string $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->token = 'debug'; //From seed
        $this->url = config('custom.app_host') . config('custom.app_http_port') . '/';
        $this->client = new Client();
    }

    public function testPost()
    {
        $response = $this->client->request('POST',
            $this->url . 'api/asset/post',
            [
                'headers' => ['Authorization' => "Bearer $this->token"],
            ]);

        $this->assertEquals("Please select label", $response->getBody());

        $response = $this->client->request('POST',
            $this->url . 'api/asset/post',
            [
                'headers' => ['Authorization' => "Bearer $this->token"],
                'form_params' => ['label' => "FRIEND"]
            ]);

        $this->assertEquals("XRP ETH BTC are supported", $response->getBody());

        $response = $this->client->request('POST',
            $this->url . 'api/asset/post',
            [
                'headers' => ['Authorization' => "Bearer $this->token"],
                'form_params' => ['label' => "FRIEND", 'currency_code' => 'IOTA']
            ]);

        $this->assertEquals("XRP ETH BTC are supported", $response->getBody());


        $response = $this->client->request('POST',
            $this->url . 'api/asset/post',
            [
                'headers' => ['Authorization' => "Bearer $this->token"],
                'form_params' => ['label' => "FRIEND", 'currency_code' => 'XRP', 'value' => -4.5]
            ]);

        $this->assertEquals("Value must be positive", $response->getBody());



    }

    public function testGet()
    {
        $response = $this->client->request('GET',
            $this->url . 'api/asset/get',
            [
                'headers' => ['Authorization' => "Bearer $this->token"],
            ]);

        $this->assertEquals(200,$response->getStatusCode());
        $this->assertEquals('[]', $response->getBody());
    }

}

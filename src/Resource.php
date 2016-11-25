<?php

namespace PhpQuickbooks;

use GuzzleHttp\Client;

abstract class Resource extends Attribute implements ResourceInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Customer constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function get(string $resource_url, string $resource_id)
    {
        $response = $this->client->get($resource_url . '/' . $resource_id);

        return json_decode($response->getBody()->getContents());
    }

    public function post(string $resource_url, array $payload)
    {
        $response = $this->client->post($resource_url, ['json' => $payload]);

        return json_decode($response->getBody()->getContents());
    }
}

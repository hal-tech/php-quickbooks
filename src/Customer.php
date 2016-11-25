<?php

namespace PhpQuickbooks;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Customer implements Resource
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Customer constructor.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $attributes)
    {
        $response = $this->client->post('customer', ['json' => $attributes]);

        var_dump(json_decode($response->getBody()->getContents(), true));
    }
}

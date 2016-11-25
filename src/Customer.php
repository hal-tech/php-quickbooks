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

    public function create(array $attributes) : self
    {
        try {
            $this->client->post('customer', ['json' => $attributes]);
        } catch(GuzzleException $e) {
            var_dump($e->getResponse()->getBody()->getContents());
        }
    }
}

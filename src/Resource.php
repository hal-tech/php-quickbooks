<?php

namespace PhpQuickbooks;

use GuzzleHttp\Client;
use stdClass;

abstract class Resource implements ResourceInterface
{
    /**
     * @var \stdClass
     */
    protected $attributes = null;

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

    public function __get($key)
    {
        $key = $this->camelCase($key);

        if (property_exists($this->attributes, $key)) {
            return $this->attributes->$key;
        }

        return null;
    }

    public function __set($key, $value)
    {
        if(property_exists($this->attributes, $key))
        {
            $this->attributes->$key = $value;
        }
    }

    public function post(string $resource_url, array $payload)
    {
        $response = $this->client->post($resource_url, ['json' => $payload]);

        return json_decode($response->getBody()->getContents());
    }

    protected function get(string $resource_url, string $customer_id)
    {
        $response = $this->client->get($resource_url . '/' . $customer_id);

        return json_decode($response->getBody()->getContents());
    }

    protected function fill(stdClass $data)
    {
        $this->attributes = $data;
    }

    protected function camelCase($key)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
    }
}

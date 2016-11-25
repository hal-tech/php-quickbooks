<?php

namespace PhpQuickbooks;

use GuzzleHttp\Client;

abstract class Resource implements ResourceInterface
{
    /**
     * @var array
     */
    protected $attributes = [];

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

        if (key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return null;
    }

    public function __set($key, $value)
    {
        if (key_exists($key, $this->attributes)) {
            $this->attributes[$key] = $value;
        }
    }

    public function post(string $resource_url, array $payload)
    {
        $response = $this->client->post($resource_url, ['json' => $payload]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function get(string $resource_url, string $customer_id)
    {
        $response = $this->client->get($resource_url . '/' . $customer_id);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function fill(array $data)
    {
        $this->attributes = $data;
    }

    protected function camelCase($key)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
    }


}

<?php

namespace PhpQuickbooks;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class Resource extends Attribute implements ResourceInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $model = '';

    /**
     * Customer constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getApiModel()
    {
        return ucwords($this->model);
    }

    protected function get(string $resource_url, string $resource_id)
    {
        $response = $this->client->get($resource_url . '/' . $resource_id);

        return json_decode($response->getBody()->getContents());
    }

    public function post(string $resource_url, array $payload)
    {

        try {
            $response = $this->client->post($resource_url, ['json' => $payload]);
            return json_decode($response->getBody()->getContents());
        } catch(RequestException $e) {
            var_dump($e->getResponse()->getBody()->getContents());
        }
    }

    public function create(array $attributes)
    {
        $response = $this->post($this->model, $attributes);

        $this->fill($response->{$this->getApiModel()});

        return $this;
    }

    public function find(string $customer_id)
    {
        $response = $this->get($this->model, $customer_id);

        $resource = new static($this->client);
        $resource->fill($response->{$this->getApiModel()});

        return $resource;
    }

    public function update(array $attributes)
    {
        return $this->fill($attributes)->save();
    }

    public function save()
    {
        $attributes = array_merge(
            $this->toArray(),
            [
                'sparse'    => true,
                'SyncToken' => $this->attributes->SyncToken,
            ]
        );

        $this->post('customer', $attributes);

        return $this;
    }
}

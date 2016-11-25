<?php

namespace PhpQuickbooks;

use GuzzleHttp\Client;

abstract class Resource extends AttributeCollection implements ResourceInterface
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
     * Resource constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param \stdClass|null     $attributes
     */
    public function __construct(Client $client, \stdClass $attributes = null)
    {
        parent::__construct($attributes);

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
        $response = $this->client->post($resource_url, ['json' => $payload]);

        return json_decode($response->getBody()->getContents());
    }

    public function create(array $attributes)
    {
        $instance = new static($this->client);

        $instance->fill($attributes);

        $response = $this->post($this->model, $instance->toArray());

        return $instance->fill($response->{$this->getApiModel()}, true);
    }

    public function find(string $customer_id)
    {
        $response = $this->get($this->model, $customer_id);

        return new static($this->client, $response->{$this->getApiModel()});
    }

    public function update(array $attributes)
    {
        return $this->fill($attributes)->save();
    }

    public function save()
    {
        $dirty = $this->getDirty();

        $attributes = array_merge($dirty, [
            'Id'        => $this->original->Id,
            'sparse'    => true,
            'SyncToken' => $this->sync_token,
        ]);


        $this->post('customer', $attributes);

        return $this;
    }
}

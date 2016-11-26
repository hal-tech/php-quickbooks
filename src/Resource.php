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
     * @var string
     */
    protected $url = null;

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

    /**
     * Returns the resources Quickbooks name.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Returns the resource url for the api.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url ?? strtolower($this->model);
    }

    /**
     * Carries out a RESTful GET request, and returns a JSON parsed response.
     *
     * @param string $resource_url
     * @param string $resource_id
     *
     * @return mixed
     */
    protected function get(string $resource_url, string $resource_id)
    {
        $response = $this->client->get($resource_url . '/' . $resource_id);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Carries out a RESTful POST request, and returns a JSON parsed response.
     *
     * @param string $resource_url
     * @param array  $payload
     *
     * @return mixed
     */
    public function post(string $resource_url, array $payload)
    {
        $response = $this->client->post($resource_url, ['json' => $payload]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Creates a new instance of the resource, and sends creation API call.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        /**
         * Creates new instance of this resource.
         */
        $instance = new static($this->client);

        /**
         * Fills with the data sent by develop, this will convert snake_case to PascalCase keys.
         */
        $instance->fill($attributes);

        /**
         * Sends the converted attributes over to the API.
         */
        $response = $this->post($this->getUrl(), $instance->toArray());

        /**
         * Fills the resource with the response, and reset original property.
         */
        return $instance->fill($response->{$this->getModel()})->resetOriginal();
    }

    public function find(string $customer_id)
    {
        $response = $this->get($this->getUrl(), $customer_id);

        return new static($this->client, $response->{$this->getModel()});
    }

    public function update(array $attributes)
    {
        $this->fill($attributes);

        return $this->save();
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

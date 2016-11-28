<?php

namespace PhpQuickbooks\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;
use PhpQuickbooks\AttributeCollection;
use PhpQuickbooks\Exceptions\QuickbooksRequestException;
use PhpQuickbooks\Query\Builder;
use Psr\Http\Message\ResponseInterface;

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
        return $this->request(function () use ($resource_url, $resource_id) {
            return $this->client->get($resource_url . '/' . $resource_id);
        });
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
        return $this->request(function () use ($resource_url, $payload) {
            return $this->client->post($resource_url, ['json' => $payload]);
        });
    }

    /**
     * Find a resource via the Quickbooks API.
     *
     * @param string $customer_id
     *
     * @return static
     */
    public function find(string $customer_id)
    {
        $response = $this->get($this->getUrl(), $customer_id);

        return new static($this->client, $response->{$this->getModel()});
    }

    /**
     * @return \PhpQuickbooks\Query\Builder
     */
    public function query()
    {
        return new Builder($this);
    }

    /**
     * @param \PhpQuickbooks\Query\Builder $builder
     *
     * @return \Illuminate\Support\Collection
     */
    public function runQuery(Builder $builder): Collection
    {
        $response = $this->request(function () use ($builder) {
            return $this->client->post(
                'query',
                ['query' => 'query=' . $builder->build()]
            );
        });

        $collection = new Collection($response->QueryResponse->{$this->getModel()});

        return $collection->map(function (\stdClass $resource) {
           return (new static($this->client))->fill($resource)->resetOriginal();
        });
    }

    /**
     * Creates a new instance of the resource, and sends creation API call.
     *
     * @param array $attributes
     *
     * @return static
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

    /**
     * Update attributes and then saves them to the API.
     *
     * @param array $attributes
     *
     * @return \PhpQuickbooks\Resources\Resource
     */
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

        $this->post($this->getUrl(), $attributes);

        return $this;
    }

    protected function request(\Closure $closure)
    {
        try {
            return $this->parseResponse($closure());
        } catch (RequestException $e) {
            throw new QuickbooksRequestException($e->getResponse());
        }
    }

    protected function parseResponse(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents());
    }
}

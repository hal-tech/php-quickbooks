<?php

namespace PhpQuickbooks\Query;

use PhpQuickbooks\Resources\ResourceInterface;

class Builder
{
    /**
     * @var \PhpQuickbooks\Resources\ResourceInterface
     */
    protected $resource;

    /**
     * @var array
     */
    protected $wheres = [];

    /**
     * @var int
     */
    protected $max = null;

    /**
     * Builder constructor.
     *
     * @param \PhpQuickbooks\Resources\ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param int $max Sets the maximum number of objects we want.
     */
    public function max(int $max)
    {
        $this->max = $max;
    }

    public function where()
    {

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->resource->runQuery($this);
    }

    /**
     * Builds our query
     *
     * @return string
     */
    public function build()
    {
        $query = "select * from {$this->resource->getModel()}";

        if($this->max) {
            $query .= ' maxresults ' . $this->max;
        }

        return $query;
    }
}

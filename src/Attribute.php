<?php

namespace PhpQuickbooks;

use stdClass;

class Attribute
{
    /**
     * @var \stdClass
     */
    protected $attributes = null;

    /**
     * Attribute constructor.
     */
    public function __construct()
    {
        $this->attributes = new stdClass();
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function camelCase(string $key): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
    }

    public function __get($key)
    {
        return $this->getAttribute($key) ?? $this->getAttribute($this->camelCase($key));
    }

    public function __set($key, $value)
    {
        if (property_exists($this->attributes, $key)) {
            $this->attributes->$key = $value;
        }
    }

    public function toArray()
    {
        $array = [];

        foreach ($this->attributes as $key => $value) {
            $array[$key] = ($value instanceof Attribute) ? $value->toArray() : $value;
        }

        return $array;
    }

    protected function fill($data)
    {
        foreach ($data as $key => $value) {
            $this->attributes->$key = ($value instanceof stdClass) ? (new Attribute())->fill($value) : $value;
        }

        return $this;
    }

    private function getAttribute($key)
    {
        if (property_exists($this->attributes, $key)) {
            return $this->attributes->$key;
        }

        return null;
    }
}

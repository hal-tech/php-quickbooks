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

    protected function fill(stdClass $data)
    {
        foreach ($data as $key => $value) {
            if($value instanceof stdClass) {
                $data->$key = (new Attribute())->fill($value);
            }
        }

        $this->attributes = $data;

        return $this;
    }

    private function getAttribute($key) {
        if (property_exists($this->attributes, $key)) {
            return $this->attributes->$key;
        }

        return null;
    }
}

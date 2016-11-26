<?php

namespace PhpQuickbooks;

use stdClass;

class AttributeCollection
{
    /**
     * @var \stdClass
     */
    protected $attributes = null;

    /**
     * @var \stdClass
     */
    protected $original = null;

    /**
     * AttributeCollection constructor.
     *
     * @param \stdClass $attributes
     */
    public function __construct(stdClass $attributes = null)
    {
        $this->attributes = new stdClass();

        if (!$attributes) {
            $attributes = new stdClass();
        }

        $this->fill($attributes)->resetOriginal();
    }

    public function __get($key)
    {
        return $this->getAttribute($key) ?? $this->getAttribute(Text::camelCase($key));
    }

    public function __set($key, $value)
    {
        if (property_exists($this->attributes, $key)) {
            $this->attributes->$key = $value;
        }
    }

    /**
     * Converts the collection to an array
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach ($this->attributes as $key => $value) {
            $array[$key] = ($value instanceof AttributeCollection) ? $value->toArray() : $value;
        }

        return $array;
    }

    /**
     * Fills the collection with data.
     *
     * Keys will always be converted to PascalCase unless
     * specified in the ignore list within the Text helper
     * class.
     *
     * @param array|stdClass $data
     *
     * @return $this
     */
    protected function fill($data)
    {
        foreach ($data as $key => $value) {
            $key = Text::pascalCase($key);

            if($value instanceof stdClass) {
                $this->attributes->$key = new AttributeCollection($value);
            } elseif(is_array($value)) {
                $this->attributes->$key = (new AttributeCollection())->fill($value);
            } else {
                $this->attributes->$key = $value;
            }
        }

        return $this;
    }

    /**
     * Resets the original property to forget any dirty properties.
     *
     * @return $this
     */
    public function resetOriginal()
    {
        $this->original = $this->cloneAttributes();

        return $this;
    }

    /**
     * Gets an array of any values that have changed since the model was created.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = [];

        foreach ($this->attributes as $key => $value) {
            if ($value instanceof AttributeCollection) {
                $sub_dirty = $value->getDirty();

                if (count($sub_dirty)) {
                    $dirty[$key] = $sub_dirty;
                }
            } else {
                if (!property_exists($this->original, $key)) {
                    $dirty[$key] = $value;
                } elseif ($value != $this->original->$key) {
                    $dirty[$key] = $value;
                }
            }
        }

        return $dirty;
    }

    /**
     * Attempts to find an attribute and return it.
     *
     * @param $key
     *
     * @return mixed
     */
    private function getAttribute($key)
    {
        if (property_exists($this->attributes, $key)) {
            return $this->attributes->$key;
        }

        return null;
    }

    /**
     * Clones the attributes. Because it is an object it will create a reference without this.
     *
     * @return \stdClass
     */
    protected function cloneAttributes()
    {
        $cloned = new stdClass;

        foreach ($this->attributes as $key => $value) {
            $cloned->$key = ($value instanceof AttributeCollection) ? $value->cloneAttributes() : $value;
        }

        return $cloned;
    }
}

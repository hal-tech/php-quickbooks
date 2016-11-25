<?php

namespace PhpQuickbooks;

/**
 * Class Customer
 *
 * @package PhpQuickbooks
 *
 * @property int    $id
 * @property string $display_name
 *
 */
class Customer extends Resource
{
    public function create(array $attributes)
    {
        $response = $this->post('customer', $attributes);

        $this->fill($response->Customer);

        return $this;
    }

    public function find(string $customer_id)
    {
        $response = $this->get('customer', $customer_id);

        $this->fill($response->Customer);

        return $this;
    }

    public function update(array $attributes)
    {
        array_merge([
            'sparse' => true,
            'Id' => $this->attributes->Id,
            'syncToken' => $this->attributes->SyncToken
        ]);

        $response = $this->post('customer', $attributes);

        $this->fill($response->Customer);

        return $this;
    }
}

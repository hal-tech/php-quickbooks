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

        $this->fill($response['Customer']);

        return $this;
    }

    public function find(string $customer_id)
    {
        $response = $this->get('customer', $customer_id);

        $this->fill($response['Customer']);

        return $this;
    }

}

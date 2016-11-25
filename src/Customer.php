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
    /**
     * Quickbooks Model
     *
     * @var string
     */
    protected $model = 'Customer';
}

<?php

namespace PhpQuickbooks\Resources\NameList;

use PhpQuickbooks\Exceptions\MethodNotImplemented;
use PhpQuickbooks\Resources\Resource;

class TaxCode extends Resource
{
    /**
     * Quickbooks Model
     *
     * @var string
     */
    protected $model = 'TaxCode';

    public function create(array $attributes)
    {
        throw new MethodNotImplemented(static::class, 'create');
    }
}

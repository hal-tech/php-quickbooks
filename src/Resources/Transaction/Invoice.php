<?php

namespace PhpQuickbooks\Resources\Transaction;

use PhpQuickbooks\AttributeCollection;
use PhpQuickbooks\Resources\Resource;

/**
 * Class Customer
 *
 * @package PhpQuickbooks
 *
 * @property int                 $id
 * @property AttributeCollection $meta_data
 * @property string $name
 * @property
 */
class Invoice extends Resource
{
    /**
     * Quickbooks Model
     *
     * @var string
     */
    protected $model = 'Invoice';
}

<?php

namespace PhpQuickbooks\Resources\NameList;

use PhpQuickbooks\Resources\Resource;

/**
 * Class Customer
 *
 * @package PhpQuickbooks
 *
 * @property int    $id
 * @property string $display_name
 *
 */
class Item extends Resource
{
    /**
     * Quickbooks Model
     *
     * @var string
     */
    protected $model = 'Item';
}

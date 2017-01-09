<?php

namespace PhpQuickbooks\Resources\NameList;

use PhpQuickbooks\Resources\Resource;

/**
 * Class Vendor
 *
 * @package PhpQuickbooks
 *
 * @property int    $id
 * @property string $display_name
 *
 */
class Vendor extends Resource
{
    /**
     * Quickbooks Model
     *
     * @var string
     */
    protected $model = 'Vendor';
}

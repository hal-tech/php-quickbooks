<?php

namespace PhpQuickbooks\Resources;

use Illuminate\Support\Collection;
use PhpQuickbooks\Query\Builder;

interface ResourceInterface
{
    public function getModel();

    public function getUrl();

    public function find(string $resource_id);

    public function create(array $attributes);

    public function update(array $attributes);

    public function save();

    public function runQuery(Builder $builder) : Collection;
}

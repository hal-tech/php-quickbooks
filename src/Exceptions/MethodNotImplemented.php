<?php

namespace PhpQuickbooks\Exceptions;

class MethodNotImplemented extends \Exception
{
    public function __construct(string $class, string $method)
    {
        parent::__construct("Method not implemented on this resource ({$class}:{$method})");
    }
}

<?php

namespace Tests\Resources\NameList;

use Illuminate\Support\Collection;
use PhpQuickbooks\Exceptions\MethodNotImplemented;
use Tests\TestCase;

class TaxCodeTest extends TestCase
{
    /** @test */
    public function it_can_query_tax_codes()
    {
        $tax_codes = $this->quickbooks->taxCode()->query()->get();

        $this->assertInstanceOf(Collection::class, $tax_codes);
        $this->assertGreaterThan(0, $tax_codes->count());
    }

    /** @test */
    public function cannot_create_tax_code()
    {
        $this->expectException(MethodNotImplemented::class);

        $this->quickbooks->taxCode()->create([
            'Name' => 'Test123'
        ]);
    }
}

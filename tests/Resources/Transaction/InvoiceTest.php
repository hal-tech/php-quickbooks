<?php

namespace Tests\Resources\Transaction;

use PhpQuickbooks\Resources\Transaction\Invoice;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    protected function createCustomer()
    {
        $name = $this->faker->name;

        return $this->quickbooks->customer()->create([
            'DisplayName' => $name,
        ]);
    }

    protected function createItem()
    {
        $name = $this->faker->sentence(3);

        return $this->quickbooks->item()->create([
            "Name"             => $name,
            "IncomeAccountRef" => [
                "value" => "79",
            ],
        ]);
    }

    /** @test */
    public function it_can_create_an_invoice()
    {
        $customer = $this->createCustomer();
        $item = $this->createItem();
        $tax_code = $this->quickbooks->taxCode()->query()->get()->last();

        $invoice = $this->quickbooks->invoice()->create([
            'CustomerRef' => [
                'value' => $customer->id,
            ],
            'Line'        => [
                [
                    'Amount'              => 2,
                    'DetailType'          => 'SalesItemLineDetail',
                    'SalesItemLineDetail' => [
                        "ItemRef" => [
                            "value" => $item->id
                        ],
                        "TaxCodeRef" => [
                            "value" => $tax_code->id
                        ]
                    ],
                ],
            ],
        ]);

        $this->assertInstanceOf(Invoice::class, $invoice);
    }
}

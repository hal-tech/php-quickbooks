<?php

namespace Tests;

use PhpQuickbooks\Customer;

class QuickbooksTest extends TestCase
{
    /** @test */
    public function it_can_create_a_customer()
    {
        $name = $this->faker->name;

        $customer = $this->quickbooks->customer()->create([
            'DisplayName' => $name
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($name, $customer->display_name);
    }

    /** @test */
    public function it_can_find_a_customer()
    {
        $name = $this->faker->name;

        $customer = $this->quickbooks->customer()->create([
            'DisplayName' => $name
        ]);

        $customer = $this->quickbooks->customer()->find($customer->id);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($name, $customer->display_name);
    }
}

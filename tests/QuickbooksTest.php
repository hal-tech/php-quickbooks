<?php

namespace Tests;

class QuickbooksTest extends TestCase
{
    /** @test */
    public function it_can_create_a_customer()
    {
        $customer = $this->quickbooks->customer()->create([
            'DisplayName' => $this->faker->name
        ]);
    }
}

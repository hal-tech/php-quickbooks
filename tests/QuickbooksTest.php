<?php

namespace Tests;

class QuickbooksTest extends TestCase
{
    /** @test */
    public function it_can_create_a_customer()
    {
        $customer = $this->quickbooks->customer()->create([
            'DisplayName' => 'John Smith22',
            'Title' => 'Mr22',
            'GivenName' => 'John22',
            'FamilyName' => 'Smith22'
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
    }
}

<?php

namespace Tests;

class QuickbooksTest extends TestCase
{
    /** @test */
    public function it_can_create_a_customer()
    {
        $customer = $this->quickbooks->customer()->create([
            'DisplayName' => 'John Smith',
            'Title' => 'Mr',
            'GivenName' => 'John',
            'FamilyName' => 'Smith'
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
    }
}

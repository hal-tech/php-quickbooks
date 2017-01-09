<?php

namespace Tests\Resources\NameList;

use PhpQuickbooks\Resources\NameList\Vendor;
use Tests\TestCase;

class VendorTest extends TestCase
{
    /** @test */
    public function it_can_create_a_vendor()
    {
        $name = $this->faker->name;

        $vendor = $this->quickbooks->vendor()->create([
            'DisplayName' => $name,
            'PrimaryEmailAddr' => [
                'Address' => $this->faker->email,
            ],
            'PrimaryPhone' => [
                'FreeFormNumber' => $this->faker->phoneNumber,
            ]
        ]);

        $this->assertInstanceOf(Vendor::class, $vendor);
        $this->assertEquals($name, $vendor->display_name);
    }

    /** @test */
    public function it_can_find_a_vendor()
    {
        $name = $this->faker->name;

        $vendor = $this->quickbooks->vendor()->create([
            'display_name' => $name,
        ]);

        $vendor = $this->quickbooks->vendor()->find($vendor->id);

        $this->assertInstanceOf(Vendor::class, $vendor);
        $this->assertEquals($name, $vendor->display_name);
    }

    /** @test */
    public function it_can_update_a_vendor()
    {
        $name1 = $this->faker->name;
        $name2 = $this->faker->name;

        $vendor = $this->quickbooks->vendor()->create([
            'display_name' => $name1,
        ]);

        $vendor->update([
            'display_name' => $name2,
            'bill_addr'    => [
                'country_sub_division_code' => 'Suffolk',
            ],
        ]);

        $vendor = $this->quickbooks->vendor()->find($vendor->id);

        $this->assertInstanceOf(Vendor::class, $vendor);
        $this->assertEquals($name2, $vendor->display_name);
    }

    /** @test */
    public function it_can_return_nested_attributes()
    {
        $name = $this->faker->name;
        $street_address = $this->faker->streetName;

        $vendor = $this->quickbooks->vendor()->create([
            'DisplayName' => $name,
            'BillAddr'    => [
                'Line1'                  => $street_address,
                'City'                   => $this->faker->city,
                'Country'                => 'GB',
                'CountrySubDivisionCode' => 'Norfolk',
                'PostalCode'             => $this->faker->postcode,
            ],
        ]);

        $vendor = $this->quickbooks->vendor()->find($vendor->id);

        $this->assertEquals($street_address, $vendor->bill_addr->line1);
        $this->assertEquals("Norfolk", $vendor->bill_addr->country_sub_division_code);
    }
}

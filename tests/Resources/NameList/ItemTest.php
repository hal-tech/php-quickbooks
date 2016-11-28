<?php

namespace Tests\Resources\NameList;

use PhpQuickbooks\Resources\NameList\Item;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /** @test */
    public function it_can_create_a_item()
    {
        $name = $this->faker->sentence(3);

        $item = $this->quickbooks->item()->create([
            "Name"             => $name,
            "IncomeAccountRef" => [
                "value" => "79",
            ],
        ]);

        $this->assertInstanceOf(Item::class, $item);

        $this->assertEquals($name, $item->name);
        $this->assertEquals("79", $item->income_account_ref->value);
    }

    /** @test */
    public function it_can_find_a_item()
    {
        $name = $this->faker->sentence(3);

        $item = $this->quickbooks->item()->create([
            "Name"             => $name,
            "IncomeAccountRef" => [
                "value" => "79",
            ],
        ]);

        $item = $this->quickbooks->item()->find($item->id);

        $this->assertInstanceOf(Item::class, $item);

        $this->assertEquals($name, $item->name);
        $this->assertEquals("79", $item->income_account_ref->value);
    }

    /** @test */
    public function it_can_update_a_item()
    {
        $name1 = $this->faker->sentence(3);
        $name2 = $this->faker->sentence(3);

        $item = $this->quickbooks->item()->create([
            "Name"             => $name1,
            "IncomeAccountRef" => [
                "value" => "79",
            ],
        ]);

        $item->update([
            'Name' => $name2,
        ]);

        $item = $this->quickbooks->item()->find($item->id);

        $this->assertInstanceOf(Item::class, $item);
        $this->assertEquals($name2, $item->name);
    }
}

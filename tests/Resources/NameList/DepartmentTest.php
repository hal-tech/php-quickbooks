<?php

namespace Tests\Resources\NameList;

use PhpQuickbooks\Resources\NameList\Department;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    /** @test */
    public function it_can_create_a_department()
    {
        $name = $this->faker->name;

        $department = $this->quickbooks->department()->create([
            'Name'               => $name,
            'FullyQualifiedName' => $name,
        ]);

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals($name, $department->name);
        $this->assertEquals($name, $department->fully_qualified_name);
    }

    /** @test */
    public function it_can_update_a_department()
    {
        $name1 = $this->faker->name;
        $name2 = $this->faker->name;

        $department = $this->quickbooks->department()->create([
            'Name'               => $name1,
            'FullyQualifiedName' => $name1,
        ]);

        $department->update([
            'Name'               => $name2,
            'FullyQualifiedName' => $name2,
        ]);

        $department = $this->quickbooks->department()->find($department->id);

        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals($name2, $department->name);
        $this->assertEquals($name2, $department->fully_qualified_name);

    }
}

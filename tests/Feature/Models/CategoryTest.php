<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
        
    public function testFields()
    {
        $this->create();
        $fields = array_keys(Category::first()->getAttributes());

        //Verifica os dados do array desprezando o id
        $this->assertEqualsCanonicalizing(
            $fields,
            [
                'id',
                'name',
                'is_active',
                'description',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        );
    }

    public function testList()
    {
        $this->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
    }

    public function testCreate()
    {
        $category = Category::create(['name' => 'Category 1']);
        $category->refresh();
        
        $this->assertEquals($category->name, "Category 1");
        $this->assertNull($category->description);
        $this->assertNull($category->deleted_at);
        $this->assertTrue($category->is_active);
        $this->assertIsString($category->id);

        $this->assertTrue(Uuid::isValid($category->id));

        $category = Category::create(['name' => 'Category 1', 'description' => null]);
        $this->assertEquals($category->description, null);

        $category = Category::create(['name' => 'Category 1', 'description' => "Description 1"]);
        $this->assertEquals($category->description, "Description 1");

        $category = Category::create(['name' => 'Category 1', 'is_active' => false]);
        $this->assertEquals($category->is_active, false);

        $category = Category::create(['name' => 'Category 1', 'is_active' => true]);
        $this->assertEquals($category->is_active, true);

    }

    public function testUpdate()
    {
        $data = [
            'name' => "Category 1", 
            'description' => null, 
            'is_active' => false, 
        ];

        $category = factory(Category::class)->create([
            'description' => "Description 1",
        ]);

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }

    }

    public function testDelete()
    {
        $category = factory(Category::class)->create()->first();
        $category->delete();

        $this->assertNotNull($category->deleted_at);

        
        $category->restore();
        $this->assertNull($category->deleted_at);
    }

    private function create()
    {
        factory(Category::class, 1)->create();
    }
}

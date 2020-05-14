<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestCategory extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        $category = factory(Category::class)->create();

        $response = $this->get(route("categories.index"));
        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);

        $response = $this->get(route("categories.show", ["category" => $category->id]));
        $response->assertJson($category->toArray());
    }

    public function testInvalidationDelete()
    {
        $response = $this->json("DELETE", route("categories.destroy", ["category" => 'a']));
        $response->assertNotFound();

        $category = factory(Category::class)->create();
        $this->json("DELETE", route("categories.destroy", ['category' => $category->id]));
        
        $this->json("DELETE", route("categories.destroy", ['category' => $category->id]));
        $response->assertSee("No query results for model");
    }

    public function testInvalidationName()
    {

        $response = $this->json('POST', route('categories.store'), []);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get("validation.required", ['attribute' => 'name'])
            ]);

        $response = $this->json('POST', route('categories.store'), [
            'name' => str_repeat("a", 256)
        ]);
        $this->assertInvalidateMax($response);


        $category = factory(Category::class)->create();
        $response = $this->json("PUT", route('categories.update', $category->id), [
            'name' => str_repeat('b', 256)
        ]);
        $this->assertInvalidateMax($response);
    }

    public function testInvalidationIs_active()
    {
        $response = $this->json('POST', route('categories.store'), ['name' => 'Category', 'is_active' => 'a']);
        $this->assertInvalidateBoolean($response);

        $category = factory(Category::class)->create();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), ['is_active' => 'a']);
        $this->assertInvalidateBoolean($response);
    }


    private function assertInvalidateMax(TestResponse $response)
    {
        $response
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => '255'])
            ]);
    }

    private function assertInvalidateBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get("validation.boolean", ['attribute' => 'is active'])
            ]);
    }
}

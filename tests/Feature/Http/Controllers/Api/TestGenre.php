<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestGenre extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testList()
    {
        $genres = factory(Genre::class)->create();

        $response = $this->get(route("genres.index"));
        $response
            ->assertStatus(200)
            ->assertJson([$genres->toArray()]);

        $response = $this->get(route("genres.show", ["genre" => $genres->id]));
        $response->assertJson($genres->toArray());
    }

    public function testInvalidationDelete()
    {
        $response = $this->json("DELETE", route("genres.destroy", ["genre" => 'a']));
        $response->assertNotFound();

        $genre = factory(Genre::class)->create();
        $this->json("DELETE", route("genres.destroy", ['genre' => $genre->id]));
        
        $this->json("DELETE", route("genres.destroy", ['genre' => $genre->id]));
        $response->assertSee("No query results for model");
    }

    public function testInvalidationName()
    {

        $response = $this->json('POST', route('genres.store'), []);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get("validation.required", ['attribute' => 'name'])
            ]);

        $response = $this->json('POST', route('genres.store'), [
            'name' => str_repeat("a", 256)
        ]);
        $this->assertInvalidateMax($response);

        $category = factory(Genre::class)->create();
        $response = $this->json("PUT", route('genres.update', $category->id), [
            'name' => str_repeat('b', 256)
        ]);
        $this->assertInvalidateMax($response);
    }

    public function testInvalidationIs_active()
    {
        $response = $this->json('POST', route('genres.store'), ['name' => 'Category', 'is_active' => 'a']);
        $this->assertInvalidateBoolean($response);

        $genre = factory(Genre::class)->create();
        $response = $this->json('PUT', route('genres.update', ["genre" => $genre->id]), ['is_active' => 'a']);
        $this->assertInvalidateBoolean($response);
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

    private function assertInvalidateMax(TestResponse $response)
    {
        $response
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }
}

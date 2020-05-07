<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GenreTest extends TestCase
{

    use DatabaseMigrations;

    public function testFields()
    {
        $genre = factory(Genre::class)->create()->first();
        $genre->refresh();
        $this->assertEqualsCanonicalizing(
            array_keys($genre->getAttributes()),
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        );
    }

    public function testList()
    {
        $genre = factory(Genre::class)->create();
        $this->assertCount(1, $genre->all());
    }

    public function testCreate()
    {
        $genre = Genre::create(['name' => 'Genre 1']);
        $genre->refresh();

        $this->assertTrue(Uuid::isValid($genre->id));
        $this->assertTrue($genre->is_active);
        $this->assertEquals($genre->name, 'Genre 1');

        $genre = Genre::create(['name' => 'Genre 1', 'is_active' => false]);
        $genre->refresh();

        $this->assertFalse($genre->is_active);
    }

    public function testUpdate()
    {
        $genre = Genre::create(['name' => 'Genre 1', 'is_active' => false]);
        $data = ["is_active" => true, 'name' => 'Genre 1'];
        $genre->refresh();

        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($genre->{$key}, $value);
        }
    }

    public function testDelete()
    {
        $genre = factory(Genre::class)->create()->first();
        $genre->delete();

        $this->assertNotNull($genre->deleted_at);
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    private $genre;

    public function setup(): void
    {
        parent::setup();
        $this->genre = new Genre();
    }

    public function testIfFillable()
    {
        $this->assertEqualsCanonicalizing(
            $this->genre->getFillable(),
            [
                'name',
                'is_active'
            ]
        );
    }

    public function testIfTrait()
    {
        $this->assertEquals(
            array_keys(class_uses($this->genre)),
            [
                SoftDeletes::class,
                Uuid::class
            ]
        );
    }

    public function testCasts()
    {
        $this->assertEqualsCanonicalizing(
            $this->genre->getCasts(),
            [
                'id' => 'string',
                'is_active' => 'boolean'
            ]
        );
    }

    public function testDates()
    {
        $this->assertEqualsCanonicalizing(
            $this->genre->getDates(),
            [
                'deleted_at',
                'created_at',
                'updated_at',
            ]
        );
    }

    public function testIncrementing()
    {
        $this->assertEquals($this->genre->getIncrementing(), false);
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private $category;
    
    /** var @Category $category */
    
    protected function setup():void
    {
        parent::setup();
        $this->category = new Category();
    }

    public function testfillable()
    {
        $this->assertEquals(
            ['name', 'is_active', 'description'],
            $this->category->getFillable()
        );
    }

    public function testIfTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $this->assertEquals($traits, array_keys(class_uses(Category::class)));
    }

    public function testIfCasts()
    {
        $casts = ['id' => "string", "is_active" => "boolean"];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIfIncrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }

    public function testDates()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $this->assertEquals(
            array_values($dates), 
            array_values($this->category->getDates())
        );
    }
}

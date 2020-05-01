<?php

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid as Uuid4Alias;

Trait Uuid {

    public static function boot() //Utilizado em todo o evento dos Models
    {
        parent::boot(); //Chamando o evento 'boot do pai
        static::creating(function($obj) {
            $obj->id = Uuid4Alias::uuid4(); // atribui o uuid ao id do registro
        });
    }
}
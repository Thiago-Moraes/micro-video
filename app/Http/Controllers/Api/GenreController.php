<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;

class GenreController extends Controller
{

    public function index()
    {
        return Genre::all();
    }

    public function store(GenreRequest $request)
    {
        $request->validated();
        return Genre::create($request->all());
    }

    public function show(Genre $genre)
    {
        return $genre;
    }

    public function update(GenreRequest $request, Genre $genre)
    {
        $request->validated();
        $genre->update($request->all());
        return $genre;
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->noContent();
    }
}

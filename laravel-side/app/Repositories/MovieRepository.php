<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository
{
    public function getAll()
    {
        return Movie::all();
    }
}

<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository
{
    public function getAll()
    {
        return Movie::all();
    }

    public function syncWithIMDB($IMDB_movies)
    {
        foreach ($IMDB_movies as $IMDB_movie) {
            // return $IMDB_movie['IMDB_id'];
            Movie::updateOrCreate(
                ['IMDB_id' => $IMDB_movie['IMDB_id']],
                $IMDB_movie
            );
        }
    }
}

<?php

namespace App\Services;

use App\Repositories\MovieRepository;

class MovieService
{
    protected $movieRepository;

    public function __construct()
    {
        $this->movieRepository = new MovieRepository();
    }

    public function getAll()
    {
        return $this->movieRepository->getAll();
    }

    public function syncWithIMDB($data)
    {
        $IMDB_movies = $data['IMDB_movies'];

        return $this->movieRepository->syncWithIMDB($IMDB_movies);
    }
}

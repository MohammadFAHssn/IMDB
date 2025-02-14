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
}

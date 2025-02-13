<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;

use Exception;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct()
    {
        $this->movieService = new MovieService();
    }

    public function getAll()
    {
        try {
            $result = $this->movieService->getAll();
        } catch (Exception $e) {
            $result = $e;
        }
        return response()->json($result);
    }
}

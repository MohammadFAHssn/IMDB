<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{

    protected $fillable = [
        'IMDB_id',
        'title',
        'type',
        'primary_image_url',
        'release_year',
        'end_year',
        'aggregate_rating',
        'vote_count',
    ];
}

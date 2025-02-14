<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();

            $table->tinyText('IMDB_id');
            $table->tinyText('title');
            $table->tinyText('type');
            $table->text('primary_image_url')->nullable();
            $table->unsignedSmallInteger('release_year');
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->double('aggregate_rating');
            $table->unsignedInteger('vote_count');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};

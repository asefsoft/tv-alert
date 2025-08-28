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
        Schema::create('tv_show_imdb_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tv_show_id');
            $table->string('imdb_id');
            $table->string('imdb_url');
            $table->integer('seasons')->nullable();
            $table->string('lang')->nullable();
            $table->integer('year')->nullable();
            $table->string('yearspan')->nullable();
            $table->integer('endyear')->nullable();
            $table->text('keywords')->nullable();
            $table->float('rating')->nullable();
            $table->integer('votes')->nullable();
            $table->timestamps();

            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->unique(['tv_show_id', 'imdb_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_show_imdb_info');
    }
};

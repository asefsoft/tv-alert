<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tv_shows', function (Blueprint $table) {
            $table->id();
            $table->string("name", 150)->index();
            $table->string("permalink", 150)->unique();
            $table->string("description", 1500);
            $table->string("status", 30)->index();
            $table->string("country", 30)->nullable();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->string("network", 30)->nullable();
            $table->string("thumb_url", 255)->nullable();
            $table->string("image_url", 250)->nullable();

            $table->dateTime("next_ep_date")->index()->nullable();

            // ep info: name, season, episode, air_date
            $table->json("last_aired_ep")->nullable()->comment("last aired episode info");
            $table->json("next_ep")->nullable()->comment("next episode to be aired");;

            $table->json("genres")->nullable();
            $table->json("pictures")->nullable();
            $table->json("episodes")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_shows');
    }
};

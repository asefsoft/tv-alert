<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tv_show_imdb_info', function (Blueprint $table) {
            $table->float('popularity_score')->nullable()->index()->after('votes');
        });
    }

    public function down(): void
    {
        Schema::table('tv_show_imdb_info', function (Blueprint $table) {
            $table->dropColumn('popularity_score');
        });
    }
};

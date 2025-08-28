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
    Schema::table('tv_shows', function (Blueprint $table) {
            $table->boolean('has_imdb_info')->default(false)->after('episodes');
            $table->dateTime('last_imdb_check_date')->nullable()->after('has_imdb_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('tv_shows', function (Blueprint $table) {
            $table->dropColumn(['has_imdb_info', 'last_imdb_check_date']);
        });
    }
};

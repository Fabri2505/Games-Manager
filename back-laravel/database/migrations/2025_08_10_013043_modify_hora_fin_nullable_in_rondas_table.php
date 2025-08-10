<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rondas', function (Blueprint $table) {
            $table->time('hora_fin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rondas', function (Blueprint $table) {
            
            DB::table('rondas')->whereNull('hora_fin')->update(['hora_fin' => '00:00:00']);

            $table->time('hora_fin')->nullable(false)->change();
        });
    }
};

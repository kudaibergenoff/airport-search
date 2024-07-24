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
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->char('code', 3)->unique()->index();
            $table->string('city_name_en')->nullable()->index();
            $table->string('city_name_ru')->nullable()->index();
            $table->string('airport_name_en')->nullable()->index();
            $table->string('airport_name_ru')->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable()->index();
            $table->decimal('longitude', 10, 7)->nullable()->index();
            $table->string('timezone')->nullable()->index();
            $table->timestamps();

            // Композитные индексы
            $table->index(['city_name_en', 'country']);
            $table->index(['city_name_ru', 'country']);
            $table->index(['airport_name_en', 'country']);
            $table->index(['airport_name_ru', 'country']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};

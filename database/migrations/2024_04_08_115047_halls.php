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
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id') ->references('id')->on('users')->onDelete('cascade');
            $table->integer('location_coordinates_id') ->references('id')->on('location_coordinates')->onDelete('cascade');
            $table->integer('work_times_id') ->references('id')->on('work_times')->onDelete('cascade');
            $table->integer('hall_capacities_id') ->references('id')->on('hall_capacities')->onDelete('cascade');
            $table->integer('ratings_id') ->references('id')->on('ratings')->onDelete('cascade');
            $table->integer('hall_type_id') ->references('id')->on('hall_types')->onDelete('cascade');
            $table->string('name');
            $table->string('region');
            $table->float('space');
            $table->float('price_per_hour');
            $table->string('license_image')->nullable();
            $table->string('panorama_image')->nullable();
            $table->string('external_image')->nullable();
           $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halls');
    }
};

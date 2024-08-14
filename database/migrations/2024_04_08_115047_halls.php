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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id') ->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('location_coordinates_id');
            $table->foreign('location_coordinates_id') ->references('id')->on('location_coordinates')->onDelete('cascade');
            $table->unsignedBigInteger('work_time_id');
            $table->foreign('work_time_id')->references('id')->on('work_times')->onDelete('cascade');
            $table->unsignedBigInteger('hall_capacity_id');
            $table->foreign('hall_capacity_id') ->references('id')->on('hall_capacities')->onDelete('cascade');
            $table->unsignedBigInteger('rating_id')->nullable();
            $table->foreign('rating_id')->references('id')->on('ratings')->onDelete('cascade');
            $table->unsignedBigInteger('hall_type_id');
            $table->foreign('hall_type_id') ->references('id')->on('hall_types')->onDelete('cascade');
            $table->string('name');
            $table->boolean('has_recorded')->default(false);
            $table->float('space');
            $table->decimal('price_per_hour',10,2);
            $table->string('license_image')->nullable();
            $table->string('panorama_image')->nullable();
            $table->string('external_image')->nullable();
            $table->integer('Reports_counter')->default(0);
           $table->boolean('is_verified')->default(false);
           $table->softDeletes();
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

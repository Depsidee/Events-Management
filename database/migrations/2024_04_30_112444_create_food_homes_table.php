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
        Schema::create('food_homes', function (Blueprint $table) {
            $table->id();
            $table->integer('home_reservations_id') ->references('id')->on('home_reservations')->onDelete('cascade');
            $table->integer('foods_id') ->references('id')->on('foods')->onDelete('cascade');
            $table->integer('amount');
            $table->double('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_homes');
    }
};

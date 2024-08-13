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
            $table->unsignedBigInteger('home_reservation_id');
            $table->foreign('home_reservation_id') ->references('id')->on('home_reservations');
            $table->unsignedBigInteger('food_id');
            $table->foreign('food_id') ->references('id')->on('food')->onDelete('cascade');
            $table->integer('amount');
            $table->decimal('total_price',10,2);;
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
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
        Schema::create('home_reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id') ->references('id')->on('users')->onDelete('cascade');

            $table->integer('decoration_id') ->references('id')->on('decorations')->onDelete('cascade');
            $table->integer('payment_id') ->references('id')->on('payments')->onDelete('cascade');
            $table->integer('photography_id') ->references('id')->on('photographies')->onDelete('cascade');
            $table->integer('location_coordinates_id') ->references('id')->on('location_coordinates')->onDelete('cascade');
            $table->boolean('has_recording');
            $table->date('date');
            $table->integer('period');
            $table->dateTime('start_time');
            $table->float('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_reservations');
    }
};

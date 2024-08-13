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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id') ->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('reservation_type_id');
            $table->foreign('reservation_type_id') ->references('id')->on('reservation_types')->onDelete('cascade');
            $table->unsignedBigInteger('decoration_id');
            $table->foreign('decoration_id') ->references('id')->on('decorations')->onDelete('cascade');
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id') ->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedBigInteger('photography_id');
            $table->foreign('photography_id') ->references('id')->on('photographies')->onDelete('cascade');
            $table->unsignedBigInteger('location_coordinates_id');
            $table->foreign('location_coordinates_id') ->references('id')->on('location_coordinates')->onDelete('cascade');
            $table->boolean('has_recorded')->default(false);
            $table->date('date');
            $table->integer('period');
            $table->time('start_time');
            $table->decimal('total_price',10,2);
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

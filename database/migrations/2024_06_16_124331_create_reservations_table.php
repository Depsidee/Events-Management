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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id') ->references('id')->on('users')->onDelete('cascade');
            $table->integer('hall_id') ->references('id')->on('halls')->onDelete('cascade');
            $table->integer('reservation_type_id') ->references('id')->on('reservation_types')->onDelete('cascade');
            $table->integer('photography_id') ->references('id')->on('photographies')->onDelete('cascade');
            $table->integer('payment_id') ->references('id')->on('payments')->onDelete('cascade');
            $table->integer('decoration_id') ->references('id')->on('decorations')->onDelete('cascade');
            $table->boolean('has_recorded');
            $table->date('date');
            $table->integer('period');
            $table->time('start_time');
            $table->decimal('total_price',10,2);;
            $table->boolean('children_permission');
            $table->boolean('transportation');
            $table->boolean('guest_photography');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

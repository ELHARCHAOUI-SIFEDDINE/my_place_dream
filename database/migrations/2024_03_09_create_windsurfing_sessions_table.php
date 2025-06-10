<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('windsurfing_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->integer('max_participants');
            $table->integer('duration_minutes');
            $table->string('difficulty_level');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('windsurfing_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('windsurfing_session_id')->constrained()->onDelete('cascade');
            $table->dateTime('booking_date');
            $table->integer('participants_count');
            $table->decimal('total_price', 8, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('special_requirements')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('windsurfing_bookings');
        Schema::dropIfExists('windsurfing_sessions');
    }
}; 
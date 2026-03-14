<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roomtours', function (Blueprint $table) {
            $table->id();
            $table->string('customer_wa');
            $table->string('duration');
            $table->dateTime('schedule');
            $table->decimal('nominal', 15, 2);
            $table->string('staff')->nullable();
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->boolean('is_split')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roomtours');
    }
};